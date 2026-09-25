<?php
declare(strict_types=1);

/**
 * Translations.
 *
 * Language files live in language/lang.<language>[.<part>].php and contain
 * lines like  define("_KEY","Text");  They are parsed (never executed).
 * Values are converted to plain UTF-8 text - HTML entities are decoded and
 * <br> becomes a newline - so templates can escape them like any other text.
 * Missing keys fall back to English, then to the key itself.
 */
final class Lang
{
    private const FALLBACK = 'english';

    private static string $current = self::FALLBACK;
    /** @var array<string, array<string,string>> */
    private static array $cache = [];

    public static function init(string $default): void
    {
        $available = self::available();

        if (isset($_GET['setlang']) && is_string($_GET['setlang']) && in_array($_GET['setlang'], $available, true)) {
            $_SESSION['lang'] = $_GET['setlang'];
            // Drop the parameter from the URL so it does not stick around.
            $query = $_GET;
            unset($query['setlang']);
            $path = strtok((string)($_SERVER['REQUEST_URI'] ?? ''), '?') ?: basename($_SERVER['SCRIPT_NAME'] ?? 'index.php');
            header('Location: ' . $path . ($query ? '?' . http_build_query($query) : ''));
            exit;
        }

        $lang = $_SESSION['lang'] ?? $default;
        if (!is_string($lang) || !in_array($lang, $available, true)) {
            $lang = in_array($default, $available, true) ? $default : self::FALLBACK;
        }
        self::$current = $lang;

        $locale = self::get('_LOCALE');
        if ($locale !== '_LOCALE') {
            @setlocale(LC_TIME, $locale . '.UTF-8', $locale);
        }
    }

    public static function current(): string
    {
        return self::$current;
    }

    public static function set(string $language): void
    {
        if (in_array($language, self::available(), true)) {
            self::$current = $language;
        }
    }

    /** Names of all languages that have a main file language/lang.<name>.php */
    public static function available(): array
    {
        static $list = null;
        if ($list === null) {
            $list = [];
            foreach (glob(AMXB_ROOT . '/language/lang.*.php') ?: [] as $file) {
                $parts = explode('.', basename($file));
                if (count($parts) === 3) {
                    $list[] = $parts[1];
                }
            }
            sort($list);
        }
        return $list;
    }

    public static function get(string $key): string
    {
        if ($key === '' || $key[0] !== '_') {
            return $key;
        }
        $keys = self::load(self::$current);
        if (isset($keys[$key])) {
            return $keys[$key];
        }
        $fallback = self::load(self::FALLBACK);
        return $fallback[$key] ?? $key;
    }

    /** @return array<string,string> */
    private static function load(string $language): array
    {
        if (isset(self::$cache[$language])) {
            return self::$cache[$language];
        }
        $keys = [];
        $files = glob(AMXB_ROOT . '/language/lang.' . $language . '.*php') ?: [];
        sort($files);
        foreach ($files as $file) {
            $content = (string)file_get_contents($file);
            $content = preg_replace('/^\xEF\xBB\xBF/', '', $content);
            if (preg_match_all('/define\s*\(\s*"([^"]+)"\s*,?\s*"(.*)"/', $content, $m, PREG_SET_ORDER)) {
                foreach ($m as [, $key, $value]) {
                    $keys[$key] = self::clean($value);
                }
            }
        }
        return self::$cache[$language] = $keys;
    }

    private static function clean(string $value): string
    {
        $value = str_replace(['\\"', "\\'"], ['"', "'"], $value);
        $value = preg_replace('#<br\s*/?>#i', "\n", $value);
        // Numeric entities in old files often lack the trailing semicolon.
        $value = preg_replace_callback('/&#(\d+);?/', fn($m) => mb_chr((int)$m[1], 'UTF-8') ?: '', $value);
        $value = preg_replace_callback('/&#x([0-9a-f]+);?/i', fn($m) => mb_chr((int)hexdec($m[1]), 'UTF-8') ?: '', $value);
        $value = html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $value = strip_tags($value);
        if (!mb_check_encoding($value, 'UTF-8')) {
            $value = mb_convert_encoding($value, 'UTF-8', 'Windows-1251,ISO-8859-1');
        }
        return $value;
    }
}

/** Shortcut: translated text for a language key. */
function __(string $key): string
{
    return Lang::get($key);
}
