<?php
declare(strict_types=1);

/**
 * Game server communication.
 *
 * @package   AMXBans
 * @license   CC-BY-NC-SA-2.0
 * @link      https://developer.valvesoftware.com/wiki/Server_queries
 */

/**
 * GoldSrc (HLDS) server communication over UDP.
 *
 *  - A2S_INFO / A2S_PLAYER / A2S_RULES queries (public information)
 *  - RCON commands (requires the rcon_password stored in _serverinfo)
 *  - the "amx_list" command of the AMXBans plugin (online players with SteamID/IP)
 *
 * ```php
 * $gs = new GameServer('1.2.3.4:27015', $server['rcon']);
 * $info = $gs->info();                         // null when offline
 * $out  = $gs->rcon('amx_reloadadmins');       // null on timeout
 * ```
 *
 * Every method returns null / [] instead of throwing when the server does not answer.
 */
final class GameServer
{
    /** @var resource|null UDP socket, opened lazily by open(). */
    private $socket = null;

    /** @var string Host name or IP address. */
    private string $host;

    /** @var int UDP port. */
    private int $port;

    /** @var string RCON password ("" = RCON disabled). */
    private string $rconPassword;

    /** @var string|null Challenge number returned by "challenge rcon". */
    private ?string $rconChallenge = null;

    /** @var float Socket timeout in seconds. */
    private float $timeout;

    /**
     * @param string $address      "host:port" as stored in _serverinfo.address (port defaults to 27015).
     * @param string $rconPassword RCON password, "" when RCON is not needed.
     * @param float  $timeout      Read/connect timeout in seconds.
     */
    public function __construct(string $address, string $rconPassword = '', float $timeout = 1.5)
    {
        [$host, $port] = self::parseAddress($address);
        $this->host = $host;
        $this->port = $port;
        $this->rconPassword = $rconPassword;
        $this->timeout = $timeout;
    }

    /**
     * Splits an address into host and port.
     *
     * @param string $address E.g. "1.2.3.4:27015".
     * @return array{0: string, 1: int} Host and port (27015 when missing).
     */
    public static function parseAddress(string $address): array
    {
        $address = trim($address);
        $port = 27015;
        if (preg_match('/^(.+):(\d{1,5})$/', $address, $m)) {
            $address = $m[1];
            $port = (int)$m[2];
        }
        return [$address, $port];
    }

    /** Closes the socket. */
    public function __destruct()
    {
        $this->close();
    }

    /**
     * Closes the UDP socket (it is reopened automatically when needed).
     *
     * @return void
     */
    public function close(): void
    {
        if (is_resource($this->socket)) {
            fclose($this->socket);
        }
        $this->socket = null;
    }

    /**
     * Opens the UDP socket (hostnames are resolved first).
     *
     * @return bool False when the address is invalid or the socket cannot be created.
     */
    private function open(): bool
    {
        if (is_resource($this->socket)) {
            return true;
        }
        if ($this->host === '' || $this->port < 1 || $this->port > 65535) {
            return false;
        }
        $ip = filter_var($this->host, FILTER_VALIDATE_IP) ? $this->host : gethostbyname($this->host);
        $target = str_contains($ip, ':') ? '[' . $ip . ']' : $ip;
        $socket = @stream_socket_client('udp://' . $target . ':' . $this->port, $errno, $errstr, $this->timeout);
        if (!$socket) {
            return false;
        }
        stream_set_timeout($socket, (int)$this->timeout, (int)(fmod($this->timeout, 1) * 1_000_000));
        $this->socket = $socket;
        return true;
    }

    /**
     * Sends a raw packet.
     *
     * @param string $data Packet including the 0xFFFFFFFF header.
     * @return bool True when the whole packet was written.
     */
    private function send(string $data): bool
    {
        return $this->open() && @fwrite($this->socket, $data) === strlen($data);
    }

    /**
     * Reads one logical response and joins GoldSrc split packets (0xFEFFFFFF).
     *
     * @return string|null Payload after the 0xFFFFFFFF header, or null on timeout.
     * @phpstan-impure Every call reads the next packet from the socket.
     */
    private function receive(): ?string
    {
        $packet = @fread($this->socket, 4096);
        if ($packet === false || $packet === '') {
            return null;
        }
        $header = substr($packet, 0, 4);
        if ($header === "\xFF\xFF\xFF\xFF") {
            return substr($packet, 4);
        }
        if ($header !== "\xFE\xFF\xFF\xFF" || strlen($packet) < 9) {
            return null;
        }
        // Split packet: [FE FF FF FF][request id: 4][packet number/total: 1][payload]
        $requestId = substr($packet, 4, 4);
        $total = ord($packet[8]) & 0x0F;
        $parts = [(ord($packet[8]) >> 4) => substr($packet, 9)];
        $guard = 0;
        while (count($parts) < $total && $guard++ < 16) {
            $next = @fread($this->socket, 4096);
            if ($next === false || $next === '' || substr($next, 4, 4) !== $requestId) {
                break;
            }
            $parts[ord($next[8]) >> 4] = substr($next, 9);
        }
        ksort($parts);
        $data = implode('', $parts);
        return str_starts_with($data, "\xFF\xFF\xFF\xFF") ? substr($data, 4) : $data;
    }

    // ------------------------------------------------------------------------
    // Public queries
    // ------------------------------------------------------------------------

    /**
     * Queries public server information (A2S_INFO), handling the S2C_CHALLENGE of newer servers.
     *
     * @return array{name: string, map: string, mod: string, game: string, appid: int, players: int,
     *               max_players: int, bots: int, dedicated: bool, os: string, password: int,
     *               secure: int, version: string, protocol: int}|null
     *         Null when the server does not answer.
     */
    public function info(): ?array
    {
        $request = "\xFF\xFF\xFF\xFFTSource Engine Query\x00";
        if (!$this->send($request) || ($data = $this->receive()) === null) {
            return null;
        }
        if ($data !== '' && $data[0] === 'A' && strlen($data) >= 5) { // challenge required (newer servers)
            if (!$this->send($request . substr($data, 1, 4)) || ($data = $this->receive()) === null) {
                return null;
            }
        }
        $r = new ByteReader($data);
        $type = $r->char();
        if ($type === 'I') {
            $info = [
                'protocol' => $r->byte(), 'name' => $r->string(), 'map' => $r->string(), 'mod' => $r->string(),
                'game' => $r->string(), 'appid' => $r->short(), 'players' => $r->byte(), 'max_players' => $r->byte(),
                'bots' => $r->byte(), 'dedicated' => $r->char(), 'os' => $r->char(), 'password' => $r->byte(),
                'secure' => $r->byte(), 'version' => $r->string(),
            ];
        } elseif ($type === 'm') { // obsolete GoldSrc response
            $info = [
                'address' => $r->string(), 'name' => $r->string(), 'map' => $r->string(), 'mod' => $r->string(),
                'game' => $r->string(), 'players' => $r->byte(), 'max_players' => $r->byte(), 'protocol' => $r->byte(),
                'dedicated' => $r->char(), 'os' => $r->char(), 'password' => $r->byte(), 'appid' => 0, 'version' => '',
            ];
            if ($r->byte() === 1) {
                $r->string(); $r->string(); $r->byte(); $r->long(); $r->long(); $r->byte(); $r->byte();
            }
            $info['secure'] = $r->byte();
            $info['bots'] = $r->byte();
        } else {
            return null;
        }
        $info['dedicated'] = strtolower((string)$info['dedicated']) === 'd';
        $info['os'] = strtolower((string)$info['os']) === 'w' ? 'Windows' : 'Linux';
        return $info;
    }

    /**
     * Lists connected players (A2S_PLAYER).
     *
     * @return list<array{name: string, frags: int, time: int}> time = seconds on the server.
     */
    public function players(): array
    {
        $data = $this->challengeQuery("\x55");
        if ($data === null || ($data[0] ?? '') !== 'D') {
            return [];
        }
        $r = new ByteReader(substr($data, 1));
        $count = $r->byte();
        $players = [];
        for ($i = 0; $i < $count && !$r->eof(); $i++) {
            $r->byte();
            $players[] = ['name' => $r->string(), 'frags' => $r->long(), 'time' => (int)$r->float()];
        }
        return $players;
    }

    /**
     * Reads public server cvars (A2S_RULES), e.g. amx_nextmap, amx_timeleft.
     *
     * @return array<string, string> cvar => value (empty on timeout).
     */
    public function rules(): array
    {
        $data = $this->challengeQuery("\x56");
        if ($data === null || ($data[0] ?? '') !== 'E') {
            return [];
        }
        $r = new ByteReader(substr($data, 1));
        $count = $r->short();
        $rules = [];
        for ($i = 0; $i < $count && !$r->eof(); $i++) {
            $rules[$r->string()] = $r->string();
        }
        return $rules;
    }

    /**
     * Sends an A2S query that needs a challenge number (players, rules).
     *
     * @param string $type Query byte, "\x55" (players) or "\x56" (rules).
     * @return string|null Response payload or null on timeout.
     */
    private function challengeQuery(string $type): ?string
    {
        if (!$this->send("\xFF\xFF\xFF\xFF" . $type . "\xFF\xFF\xFF\xFF") || ($data = $this->receive()) === null) {
            return null;
        }
        if (($data[0] ?? '') === 'A' && strlen($data) >= 5) {
            if (!$this->send("\xFF\xFF\xFF\xFF" . $type . substr($data, 1, 4))) {
                return null;
            }
            $data = $this->receive();
        }
        return $data;
    }

    // ------------------------------------------------------------------------
    // RCON
    // ------------------------------------------------------------------------

    /**
     * Executes an RCON command.
     *
     * The command must already be sanitized by the caller (see rcon_safe());
     * commands containing line breaks are refused.
     *
     * @param string $command      Console command, e.g. "amx_reloadadmins".
     * @param int    $extraPackets Additional response packets to read for long outputs.
     * @return string|null Response text, or null on timeout / missing password.
     */
    public function rcon(string $command, int $extraPackets = 0): ?string
    {
        if ($this->rconPassword === '' || str_contains($this->rconPassword, '"') || preg_match('/[\r\n]/', $command)) {
            return null;
        }
        if ($this->rconChallenge === null) {
            if (!$this->send("\xFF\xFF\xFF\xFFchallenge rcon\n") || ($data = $this->receive()) === null) {
                return null;
            }
            if (!preg_match('/challenge rcon (\d+)/', $data, $m)) {
                return null;
            }
            $this->rconChallenge = $m[1];
        }
        $packet = "\xFF\xFF\xFF\xFFrcon {$this->rconChallenge} \"{$this->rconPassword}\" {$command}\n";
        if (!$this->send($packet)) {
            return null;
        }
        $response = '';
        for ($i = 0; $i <= $extraPackets; $i++) {
            $data = $this->receive();
            if ($data === null) {
                break;
            }
            $response .= ltrim(substr($data, 0, 1) === 'l' ? substr($data, 1) : $data, "\x00");
        }
        return $i === 0 && $response === '' ? null : rtrim($response, "\x00\n");
    }

    /**
     * Detects the "Bad rcon_password." answer.
     *
     * @param string|null $response Result of rcon().
     * @return bool True when the password was rejected.
     */
    public static function isBadPassword(?string $response): bool
    {
        return $response !== null && stripos($response, 'Bad rcon_password') !== false;
    }

    /**
     * Online players via the AMXBans plugin command "amx_list".
     *
     * The plugin answers with lines of fields separated by 0xFC:
     * name, userid, steamid, ip[:port], status (0 player, 1 bot, 2 HLTV), immunity.
     *
     * @return list<array{name: string, userid: int, steamid: string, ip: string, status: int, immunity: int}>|null
     *         Null = no answer; [] = nobody online or wrong RCON password.
     */
    public function amxList(): ?array
    {
        $raw = $this->rcon('amx_list', 3);
        if ($raw === null || self::isBadPassword($raw)) {
            return $raw === null ? null : [];
        }
        $raw = str_replace("\xFB\xFB\xFB\xFB", '', $raw);
        $players = [];
        foreach (explode("\n", $raw) as $line) {
            $f = explode("\xFC", trim($line));
            if (count($f) < 5) {
                continue;
            }
            $players[] = [
                'name'     => mb_convert_encoding($f[0], 'UTF-8', 'UTF-8'),
                'userid'   => (int)$f[1],
                'steamid'  => $f[2],
                'ip'       => explode(':', $f[3])[0],
                'status'   => (int)$f[4],
                'immunity' => (int)($f[5] ?? 0),
            ];
        }
        return $players;
    }
}

/**
 * Little-endian reader for Valve query responses.
 *
 * Reading past the end returns 0 / "" instead of failing.
 */
final class ByteReader
{
    /** @var int Current read offset. */
    private int $pos = 0;

    /**
     * @param string $data Packet payload.
     */
    public function __construct(private readonly string $data)
    {
    }

    /** @return bool True when all bytes were read. */
    public function eof(): bool
    {
        return $this->pos >= strlen($this->data);
    }

    /** @return int Unsigned 8-bit integer. */
    public function byte(): int
    {
        return $this->eof() ? 0 : ord($this->data[$this->pos++]);
    }

    /** @return string One byte as a character. */
    public function char(): string
    {
        return $this->eof() ? '' : $this->data[$this->pos++];
    }

    /** @return int Unsigned 16-bit little-endian integer. */
    public function short(): int
    {
        $v = unpack('v', substr($this->data, $this->pos, 2) . "\0\0")[1];
        $this->pos += 2;
        return $v;
    }

    /** @return int Signed 32-bit little-endian integer. */
    public function long(): int
    {
        $v = unpack('l', substr($this->data, $this->pos, 4) . "\0\0\0\0")[1];
        $this->pos += 4;
        return $v;
    }

    /** @return float 32-bit little-endian float. */
    public function float(): float
    {
        $v = unpack('g', substr($this->data, $this->pos, 4) . "\0\0\0\0")[1];
        $this->pos += 4;
        return $v;
    }

    /** @return string NUL-terminated string, converted to UTF-8 (Windows-1252 fallback). */
    public function string(): string
    {
        $end = strpos($this->data, "\0", $this->pos);
        if ($end === false) {
            $end = strlen($this->data);
        }
        $s = substr($this->data, $this->pos, $end - $this->pos);
        $this->pos = $end + 1;
        return mb_check_encoding($s, 'UTF-8') ? $s : mb_convert_encoding($s, 'UTF-8', 'Windows-1252');
    }
}
