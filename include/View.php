<?php
declare(strict_types=1);

/**
 * Template engine wrapper.
 *
 * @package   AMXBans
 * @license   CC-BY-NC-SA-2.0
 * @see       docs/templates.md
 */

use Smarty\Smarty;

/**
 * Smarty 5 configured for AMXBans, with automatic HTML escaping of every {$variable}.
 *
 * Use `{$html nofilter}` only for values that are already safe HTML
 * (e.g. the output of the |bbcode modifier, which escapes its input first).
 *
 * Registered plugins (see docs/templates.md for examples):
 *
 * | Plugin                 | Type     | Implemented by            |
 * |------------------------|----------|---------------------------|
 * | lang                   | modifier | {@see Lang::get()}        |
 * | duration, banlength    | modifier | format_duration(), format_ban_length() |
 * | datetime, relative     | modifier | format_datetime(), format_relative()   |
 * | filesize               | modifier | format_filesize()         |
 * | bbcode                 | modifier | bbcode_to_html() - use with nofilter    |
 * | steamprofile           | modifier | steam_profile_url()       |
 * | flag, gameicon         | modifier | country_flag(), game_icon() |
 * | safeurl                | modifier | safe_url()                |
 * | sprintf, contains, initial | modifier | format_sprintf(), stripos(), first letter |
 * | csrf                   | function | hidden "_token" input     |
 * | icon                   | function | svg_icon() - Heroicons    |
 *
 * Usage in a controller:
 * ```php
 * $view->page('ban_list.tpl', ['bans' => $bans], '_TITLEBANLIST');
 * ```
 */
final class View extends Smarty
{
    /** @var stdClass Configuration + web settings (design, banner, …). */
    private stdClass $config;

    /**
     * Configures template directories, escaping and plugins.
     *
     * The selected design directory is searched first and templates/modern/
     * second, so a design only needs to contain the templates it changes.
     *
     * @param stdClass $config Configuration; $config->design is validated and
     *                         reset to "modern" when the design has no layout.tpl.
     */
    public function __construct(stdClass $config)
    {
        parent::__construct();
        $this->config = $config;

        $design = preg_replace('/[^A-Za-z0-9_-]/', '', (string)($config->design ?? '')) ?: 'modern';
        if (!is_file(AMXB_ROOT . '/templates/' . $design . '/layout.tpl')) {
            $design = 'modern';
        }
        $config->design = $design;

        // A design only has to contain the templates it changes (at least layout.tpl);
        // everything else falls back to the "modern" templates.
        $dirs = [AMXB_ROOT . '/templates/' . $design . '/'];
        if ($design !== 'modern') {
            $dirs[] = AMXB_ROOT . '/templates/modern/';
        }
        $this->setTemplateDir($dirs);
        $this->setCompileDir(AMXB_ROOT . '/templates_c/');
        $this->setCacheDir(AMXB_ROOT . '/templates_c/cache/');
        $this->setEscapeHtml(true);
        $this->setCaching(Smarty::CACHING_OFF);
        $this->setCompileCheck(Smarty::COMPILECHECK_ON);
        $this->muteUndefinedOrNullWarnings();

        $this->registerPlugin('modifier', 'lang', [Lang::class, 'get']);
        $this->registerPlugin('modifier', 'duration', 'format_duration');
        $this->registerPlugin('modifier', 'banlength', 'format_ban_length');
        $this->registerPlugin('modifier', 'datetime', 'format_datetime');
        $this->registerPlugin('modifier', 'filesize', 'format_filesize');
        $this->registerPlugin('modifier', 'bbcode', 'bbcode_to_html');
        $this->registerPlugin('modifier', 'steamprofile', 'steam_profile_url');
        $this->registerPlugin('modifier', 'flag', 'country_flag');
        $this->registerPlugin('modifier', 'gameicon', 'game_icon');
        $this->registerPlugin('modifier', 'safeurl', 'safe_url');
        $this->registerPlugin('modifier', 'relative', 'format_relative');
        $this->registerPlugin('modifier', 'sprintf', 'format_sprintf');
        $this->registerPlugin('modifier', 'contains', fn($haystack, $needle) => stripos((string)$haystack, (string)$needle) !== false);
        $this->registerPlugin('modifier', 'initial', fn($s) => mb_strtoupper(mb_substr((string)$s, 0, 1)));
        $this->registerPlugin('function', 'csrf', fn() => '<input type="hidden" name="_token" value="' . Security::csrfToken() . '">');
        $this->registerPlugin('function', 'icon', 'svg_icon');
    }

    /**
     * Renders a page and ends the request.
     *
     * @param string               $template Template path relative to the design directory,
     *                                       normally one that extends layout.tpl or admin/layout.tpl.
     * @param array<string, mixed> $vars     Template variables.
     * @param string               $title    Language key (or text) for the <title>.
     * @return never
     */
    public function page(string $template, array $vars = [], string $title = ''): never
    {
        $this->assign($vars);
        $this->assign('page_title', $title);
        $this->assignCommon();
        header('Content-Type: text/html; charset=UTF-8');
        $this->display($template);
        exit;
    }

    /**
     * Assigns the variables every layout needs.
     *
     * - `app`: version, banner, design, current script, languages, feature flags
     * - `auth`: logged-in admin without password/logcode (null for guests)
     * - `perms`: permission => yes|no|own
     * - `csrf_token`, `nav` (public menu), `flashes`, `asset_ver`
     *
     * @return void
     */
    public function assignCommon(): void
    {
        $user = Auth::user();
        if ($user) {
            unset($user['password'], $user['logcode']);
        }
        $this->assign([
            'app' => [
                'version'    => AMXB_VERSION,
                'banner'     => (string)($this->config->banner ?? ''),
                'banner_url' => safe_url((string)($this->config->banner_url ?? '')),
                'design'     => $this->config->design,
                'script'     => basename($_SERVER['SCRIPT_NAME'] ?? 'index.php'),
                'lang'       => Lang::current(),
                'html_lang'  => strtolower(substr(Lang::get('_LOCALE'), 0, 2)) ?: 'en',
                'languages'  => Lang::available(),
                'use_comment'=> (int)($this->config->use_comment ?? 0),
                'use_demo'   => (int)($this->config->use_demo ?? 0),
            ],
            'auth'       => $user,
            'perms'      => Auth::permissions(),
            'csrf_token' => Security::csrfToken(),
            'nav'        => menu_items(Auth::check()),
            'flashes'    => flash_pull(),
            'asset_ver'  => asset_version(),
        ]);
    }
}
