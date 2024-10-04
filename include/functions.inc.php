<?php

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

function check_size($value,$minsize,$maxsize,$prefixlang) {
  if(!$value && $minsize) {
    return "_NO".$prefixlang;
  } else if(strlen($value) < $minsize) {
    return "_".$prefixlang."TOSHORT";
  } else if(strlen($value) > $maxsize) {
    return "_".$prefixlang."TOLONG"; 
  }
}

// validate string
//
// value: STRING
// types: name, email, steamid, ip, amxxaccess, amxxflags
// minsize: INT
// maxsize: INT
// prefixlang: STRING
function validate_value($value,$type='name',&$msg="",$minsize=1,$maxsize=31,$prefixlang="") {

  switch($type) {
    case 'name':
      $msg=check_size($value,$minsize,$maxsize,$prefixlang);
      if($msg) return false;
      return true;
      break;
    case 'email':
      #if(!preg_match("/^[0-9,a-z,A-Z_%+-]{2,}@[0-9,a-z,A-Z]{2,}.[0-9,a-z,A-Z]{2,6}$/",$value)) { $msg="_EMAILINVALID"; return false; }
      if(!preg_match("/^[a-zA-Z0-9-_.]{2,}@[a-zA-Z0-9-_.]{2,}.[a-zA-Z]{2,6}$/",$value)) { $msg="_EMAILINVALID"; return false; }
      return true;
      break;
    case 'steamid':
      if(!preg_match("/^STEAM_0:(0|1):[0-9]{1,10}$/",$value)) { $msg="_STEAMIDINVALID"; return false; }
      return true;
      break;
    case 'ip':
      if(!preg_match("/^[0-9]{1,3}.[0-9]{1,3}.[0-9]{1,3}.[0-9]{1,3}$/",$value)) { $msg="_IPINVALID"; return false; }
      return true;
      break;
    case 'amxxaccess':
      if(!preg_match("/^[a-u,z]{1,22}$/",$value)) { $msg="_ACCESSINVALID"; return false; }
      return true;
      break;
    case 'amxxflags':
      if((strrpos($value,"b")!==false && strrpos($value,"c")!==false)
        || (strrpos($value,"b")!==false && strrpos($value,"d")!==false)
        || (strrpos($value,"c")!==false && strrpos($value,"d")!==false)) { $msg="_FLAGSINVALID"; return false; }
      if(strrpos($value,"a")===false && strrpos($value,"b")===false && strrpos($value,"c")===false && strrpos($value,"d")===false) { $msg="_FLAGSBCDMISSING"; return false; }
      if(!preg_match("/^[a-e,k]{1,4}$/",$value)) { $msg="_FLAGSINVALID"; return false; }
      return true;
      break;
    default:
      return false;
      break;
  }
  return false;

}
function sql_safe($value) {
  global $config;
  $mysql = mysqli_connect($config->db_host,$config->db_user,$config->db_pass, $config->db_db);
  if ( function_exists('get_magic_quotes_gpc') ) $value=stripslashes_recursive($value); //function in config.inc.php
  return mysqli_real_escape_string($mysql, $value);
}
function html_safe($value) {
  if ( function_exists('get_magic_quotes_gpc') ) $value=stripslashes_recursive($value); //function in config.inc.php
  return htmlspecialchars($value, ENT_QUOTES);
}

function _substr($str, $length, $minword = 3) {
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

function construct_vb_page_nav($current, $total, $pagenavpages, $pagenavsarr) {
  $result = array();

  if ($current > 1) {
    $result['prev'] = $current - 1;
  } else {
    $result['prev'] = false;
  }
  if ($current < $total) {
    $result['next'] = $current + 1;
  } else {
    $result['next'] = false;
  }

  $curpage = 0;
  $result['pages'] = array();
  $result['first'] = false;
  $result['last'] = false;
  while ($curpage++ < $total)
  {
    if (abs($curpage - $current) >= $pagenavpages && $pagenavpages != 0) {
      if ($curpage == 1) {
        $result['first'] = $curpage;
      }
      if ($curpage == $total) {
        $result['last'] = $curpage;
      }

      // generate relative links (eg. +10,etc).
      if (in_array(abs($curpage - $current), $pagenavsarr) && $curpage != 1 && $curpage != $total) {
        $result['pages'][] = array('number' => $curpage, 'current' => false);
      }
    } else {
      if ($curpage == $current) {
        $result['pages'][] = array('number' => $curpage, 'current' => true);
      }
      else {
        $result['pages'][] = array('number' => $curpage, 'current' => false);
      }
    }
  }

  return $result;
}
?>