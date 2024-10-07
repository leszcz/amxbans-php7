<?php
declare(strict_types=1);

/*   
  AMXBans v6.0
  
  Copyright 2009, 2010 by SeToY & |PJ|ShOrTy

  This file is part of AMXBans.

    AMXBans is free software, but it's licensed under the
  Creative Commons - Attribution-NonCommercial-ShareAlike 2.0

    AMXBans is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.

    You should have received a copy of the cc-nC-SA along with AMXBans.  
  If not, see <http://creativecommons.org/licenses/by-nc-sa/2.0/>.
*/

function check_size(string $value, int $minsize, int $maxsize, string $prefixlang): ?string {
    if (!$value && $minsize) {
        return "_NO" . $prefixlang;
    } elseif (strlen($value) < $minsize) {
        return "_" . $prefixlang . "TOSHORT";
    } elseif (strlen($value) > $maxsize) {
        return "_" . $prefixlang . "TOLONG"; 
    }
    return null;
}

function validate_value(
    string $value, 
    string $type = 'name', 
    string &$msg = "", 
    int $minsize = 1, 
    int $maxsize = 31, 
    string $prefixlang = ""
): bool {
    switch($type) {
        case 'name':
            $msg = check_size($value, $minsize, $maxsize, $prefixlang) ?? "";
            return empty($msg);
        case 'email':
            if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                $msg = "_EMAILINVALID";
                return false;
            }
            return true;
        case 'steamid':
            if (!preg_match("/^STEAM_0:(0|1):[0-9]{1,10}$/", $value)) {
                $msg = "_STEAMIDINVALID";
                return false;
            }
            return true;
        case 'ip':
            if (!filter_var($value, FILTER_VALIDATE_IP)) {
                $msg = "_IPINVALID";
                return false;
            }
            return true;
        case 'amxxaccess':
            if (!preg_match("/^[a-u,z]{1,22}$/", $value)) {
                $msg = "_ACCESSINVALID";
                return false;
            }
            return true;
        case 'amxxflags':
            if ((str_contains($value, "b") && str_contains($value, "c"))
                || (str_contains($value, "b") && str_contains($value, "d"))
                || (str_contains($value, "c") && str_contains($value, "d"))) {
                $msg = "_FLAGSINVALID";
                return false;
            }
            if (!str_contains($value, "a") && !str_contains($value, "b") && !str_contains($value, "c") && !str_contains($value, "d")) {
                $msg = "_FLAGSBCDMISSING";
                return false;
            }
            if (!preg_match("/^[a-e,k]{1,4}$/", $value)) {
                $msg = "_FLAGSINVALID";
                return false;
            }
            return true;
        default:
            return false;
    }
}

function sql_safe(string $value): string {
    $pdo = getPDO();
    return $pdo->quote($value);
}

function html_safe(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

function _substr(string $str, int $length, int $minword = 3): string {
    $sub = '';
    $len = 0;
    
    foreach (explode(' ', $str) as $word) {
        $part = (($sub != '') ? ' ' : '') . $word;
        $sub .= $part;
        $len += strlen($part);
        
        if (strlen($word) > $minword && strlen($sub) >= $length) {
            break;
        }
    }
    
    return $sub . (($len < strlen($str)) ? '...' : '');
}

function construct_vb_page_nav(int $current, int $total, int $pagenavpages, array $pagenavsarr): array {
    $result = [
        'prev' => $current > 1 ? $current - 1 : false,
        'next' => $current < $total ? $current + 1 : false,
        'pages' => [],
        'first' => false,
        'last' => false
    ];

    for ($curpage = 1; $curpage <= $total; $curpage++) {
        if (abs($curpage - $current) >= $pagenavpages && $pagenavpages != 0) {
            if ($curpage == 1) {
                $result['first'] = $curpage;
            }
            if ($curpage == $total) {
                $result['last'] = $curpage;
            }

            // generate relative links (eg. +10,etc).
            if (in_array(abs($curpage - $current), $pagenavsarr) && $curpage != 1 && $curpage != $total) {
                $result['pages'][] = ['number' => $curpage, 'current' => false];
            }
        } else {
            $result['pages'][] = ['number' => $curpage, 'current' => ($curpage == $current)];
        }
    }

    return $result;
}
?>