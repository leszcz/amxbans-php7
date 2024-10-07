<?php
session_start();

if (!isset($_POST["bid"]) || !is_numeric($_POST["bid"])) {
    header("Location: index.php");
    exit;
}

$title2 = "_TITLEBANDETAIL";
$bid = (int)$_POST["bid"];
$site = isset($_POST["site"]) && is_numeric($_POST["site"]) ? (int)$_POST["site"] : 1;

// Create new captcha if needed
if (!(isset($_POST["add_comment"]) || isset($_POST["add_demo"]))) {
    new_captcha();
}

function new_captcha() {
    $rand = substr(base64_encode(mt_rand(1000000, 9999999)), 0, 7);
    $rand = strtr($rand, ['J' => 'Z', 'I' => 'Y', 'j' => 'z', 'i' => 'y', '0' => 'B', 'O' => 'C']);
    $_SESSION["captcha_code"] = $rand;
}

// Ban edit
if (isset($_POST["edit_ban"]) && isset($_POST["bid"])) {
    if (!has_access("bans_edit")) {
        $error = "_ACCESSINVALID";
        header("Location: index.php");
        exit;
    }

    $pdo = getPDO();
    $unban = isset($_POST["unban"]) && $_POST["unban"] === "on";
    
    if ($unban && !has_access("bans_unban")) {
        $error = "_ACCESSINVALID";
    }

    $ban_length_old = (int)$_POST["ban_length_old"];
    $player_nick = sql_safe($_POST["player_nick"]);
    $player_id = sql_safe($_POST["player_id"]);
    $player_ip = sql_safe($_POST["player_ip"]);
    $ban_type = $_POST["ban_type"];
    $ban_reason = sql_safe($_POST["ban_reason"]);
    $ban_length = (int)$_POST["ban_length"];
    $ban_created = (int)$_POST["ban_created"];
    $edit_reason = sql_safe($_POST["edit_reason"]);

    if ($unban) {
        $ban_length = -1;
    } else {
        if (!validate_value($player_nick, "name", $msg, 1, 31, "NICKNAME")) $error[] = $msg;
        if (!validate_value($player_id, "steamid", $msg) && $ban_type == "S") $error[] = $msg;
        if (!validate_value($player_ip, "ip", $msg) && $ban_type == "SI") $error[] = $msg;
    }

    if ($unban) $edit_reason = "Unban: " . $edit_reason;

    if (empty($error)) {
        // Insert ban edit log
        $stmt = $pdo->prepare("INSERT INTO `{$config->db_prefix}_bans_edit` (`bid`, `edit_time`, `admin_nick`, `edit_reason`) VALUES (?, UNIX_TIMESTAMP(), ?, ?)");
        $stmt->execute([$bid, $_SESSION["uname"], $edit_reason]);

        if ($unban) {
            $ban_row = sql_get_ban_details($bid);
            $player_nick = $ban_row["player_nick"];
            $player_id = $ban_row["player_id"];

            $edit_query = "UPDATE `{$config->db_prefix}_bans` SET `ban_length` = -1, `expired` = 1";
        } else {
            $edit_query = "UPDATE `{$config->db_prefix}_bans` SET 
                `player_nick` = :player_nick,
                `player_id` = :player_id,
                `player_ip` = :player_ip,
                `ban_type` = :ban_type,
                `ban_reason` = :ban_reason,
                `cs_ban_reason` = :ban_reason";

            if ($ban_length_old !== $ban_length) {
                $edit_query .= ", `ban_length` = :ban_length";
            }

            if ($ban_length == 0) {
                $edit_query .= ", `expired` = 0";
            } elseif (($ban_created + $ban_length * 60) < time()) {
                $edit_query .= ", `expired` = 1";
            } else {
                $edit_query .= ", `expired` = 0";
            }

            $stmt = $pdo->prepare($edit_query . " WHERE `bid` = :bid");
            $stmt->execute([
                ':player_nick' => $player_nick,
                ':player_id' => $player_id,
                ':player_ip' => $player_ip,
                ':ban_type' => $ban_type,
                ':ban_reason' => $ban_reason,
                ':ban_length' => $ban_length,
                ':bid' => $bid
            ]);
        }
        log_to_db("Ban edit", ($unban ? "Unban" : "Edited ban") . ": ID $bid (<$player_nick> <$player_id>)");
    }
}

// Ban delete
if (isset($_POST["del_ban_x"]) && isset($_POST["bid"])) {
    if (!has_access("bans_delete")) {
        $error = "_ACCESSINVALID";
        header("Location: index.php");
        exit;
    }

    $pdo = getPDO();
    $stmt = $pdo->prepare("SELECT `id`, `demo_file` FROM `{$config->db_prefix}_files` WHERE `bid` = ?");
    $stmt->execute([$bid]);

    while ($result = $stmt->fetch()) {
        if (file_exists("include/files/" . $result["demo_file"])) {
            if (file_exists("include/files/" . $result["demo_file"] . "_thumb")) {
                unlink("include/files/" . $result["demo_file"] . "_thumb");
            }
            unlink("include/files/" . $result["demo_file"]);
        }
        $pdo->prepare("DELETE FROM `{$config->db_prefix}_files` WHERE `id` = ? LIMIT 1")->execute([$result["id"]]);
    }

    $pdo->prepare("DELETE FROM `{$config->db_prefix}_comments` WHERE `bid` = ?")->execute([$bid]);
    $ban_row = sql_get_ban_details($bid);

    $pdo->prepare("DELETE FROM `{$config->db_prefix}_bans` WHERE `bid` = ? LIMIT 1")->execute([$bid]);
    log_to_db("Ban edit", "Deleted ban: ID $bid (<{$ban_row["player_nick"]}> <{$ban_row["player_id"]}>)");

    header("Location: index.php");
    exit;
}

// Comment delete
if (isset($_POST["del_comment_x"]) && isset($_POST["cid"]) && $_SESSION["loggedin"]) {
    if (!has_access("bans_delete")) {
        $error = "_ACCESSINVALID";
        header("Location: index.php");
        exit;
    }

    $pdo = getPDO();
    $pdo->prepare("DELETE FROM `{$config->db_prefix}_comments` WHERE `id` = ? LIMIT 1")->execute([(int)$_POST["cid"]]);
}

// Comment add
if (isset($_POST["add_comment"]) && $bid) {
    if (($_SESSION["captcha_code"] !== 0 || $_POST["verify"] !== $_SESSION["captcha_code"]) && !$_SESSION["loggedin"]) {
        $error[] = "_WRONGCAPTCHA";
    }

    if (empty($error)) {
        $pdo = getPDO();
        $pdo->prepare("INSERT INTO `{$config->db_prefix}_comments` (`name`, `comment`, `email`, `addr`, `date`, `bid`) 
            VALUES (?, ?, ?, ?, UNIX_TIMESTAMP(), ?)")
            ->execute([$name, $comment, $email, $_SERVER["REMOTE_ADDR"], $bid]);
    }

    new_captcha();
}

// Comment edit
if (isset($_POST["edit_comment"]) && isset($_POST["cid"]) && $_SESSION["loggedin"]) {
  if (!has_access("bans_edit")) {
      header("Location: index.php");
      exit;
  }

  $pdo = getPDO();
  $stmt = $pdo->prepare("UPDATE `{$config->db_prefix}_comments` SET `comment` = ?, `name` = ?, `email` = ? WHERE `id` = ?");
  $stmt->execute([$comment, $name, $email, (int)$_POST["cid"]]);
  $msg_comment = "_COMEDITED";
}

// File delete
if (isset($_POST["del_demo_x"]) && isset($_POST["did"]) && $_SESSION["loggedin"]) {
  if (!has_access("bans_delete")) {
      header("Location: index.php");
      exit;
  }

  $pdo = getPDO();
  $stmt = $pdo->prepare("SELECT `demo_file` FROM `{$config->db_prefix}_files` WHERE `id` = ?");
  $stmt->execute([(int)$_POST["did"]]);
  $file = $stmt->fetchColumn();

  if ($file) {
      if (file_exists("include/files/" . $file . "_thumb")) {
          unlink("include/files/" . $file . "_thumb");
      }
      if (file_exists("include/files/" . $file)) {
          unlink("include/files/" . $file);
          $stmt = $pdo->prepare("DELETE FROM `{$config->db_prefix}_files` WHERE `id` = ? LIMIT 1");
          $stmt->execute([(int)$_POST["did"]]);
          $msg_demo = "_FILEDELSUCCESS";
      } else {
          $msg_demo = "_FILENOTFOUND";
      }
  }
}

// File edit
if (isset($_POST["edit_demo"]) && isset($_POST["did"]) && $_SESSION["loggedin"]) {
  if (!has_access("bans_edit")) {
      header("Location: index.php");
      exit;
  }

  $pdo = getPDO();
  $stmt = $pdo->prepare("UPDATE `{$config->db_prefix}_files` SET `comment` = ?, `name` = ?, `email` = ? WHERE `id` = ?");
  $stmt->execute([$comment, $name, $email, (int)$_POST["did"]]);
  $msg_demo = "_FILEEDITED";
}

// File add
if (isset($_POST["add_demo"]) && isset($_FILES['filename']['tmp_name'])) {
  global $config;

  $pdo = getPDO();
  $real_file = $_FILES['filename']['name'];

  if (($_SESSION["captcha_code"] != 0 || $_POST["verify"] != $_SESSION["captcha_code"]) && $_SESSION["loggedin"] !== true) {
      $error[] = "_WRONGCAPTCHA";
  }

  $types = explode(",", $config->file_type);
  $file_type = strtolower(pathinfo($real_file, PATHINFO_EXTENSION));

  if (!$real_file) {
      $error[] = "_FILENOFILE";
  } elseif (!in_array($file_type, $types)) {
      $error[] = "_FILETYPENOTALLOWED";
  }

  if ($_FILES['filename']['size'] >= ($config->max_file_size * 1024 * 1024)) {
      $error[] = "_FILETOBIG";
  }

  if (empty($error)) {
      $temp_file = md5(microtime() . uniqid(rand(), true)) . "_" . $bid;
      if (move_uploaded_file($_FILES['filename']['tmp_name'], "include/files/" . $temp_file)) {
          if (extension_loaded("gd")) {
              mkthumb($temp_file);
          }
      } else {
          $error[] = "_FILEUPLOADFAIL";
      }
  }

  if (empty($error)) {
      $stmt = $pdo->prepare("INSERT INTO `{$config->db_prefix}_files` (`upload_time`, `down_count`, `bid`, `demo_file`, `demo_real`, `comment`, `name`, `email`, `file_size`, `addr`) 
                             VALUES (UNIX_TIMESTAMP(), 0, ?, ?, ?, ?, ?, ?, ?, ?)");
      $stmt->execute([$bid, $temp_file, $real_file, $comment, $name, $email, $_FILES['filename']['size'], $_SERVER["REMOTE_ADDR"]]);
      $msg_demo = "_FILEUPLOADSUCCESS";
  }

  new_captcha();
}

// File download
if (isset($_POST["down_demo_x"]) && isset($_POST["did"])) {
  global $config;

  $pdo = getPDO();
  $stmt = $pdo->prepare("SELECT `demo_file`, `demo_real`, `file_size` FROM `{$config->db_prefix}_files` WHERE `id` = ? LIMIT 1");
  $stmt->execute([(int)$_POST["did"]]);
  $result = $stmt->fetch();

  if ($result) {
      $file_local = $config->path_root . "/include/files/" . $result['demo_file'];
      $file_real = $result['demo_real'];

      if (!file_exists($file_local)) {
          $error[] = "_FILENOTAVAILABLE";
      }

      if (empty($error)) {
          $stmt = $pdo->prepare("UPDATE `{$config->db_prefix}_files` SET `down_count` = `down_count` + 1 WHERE `id` = ?");
          $stmt->execute([(int)$_POST["did"]]);

          if (ini_get('zlib.output_compression')) {
              ini_set('zlib.output_compression', 'Off');
          }

          header("Content-Type: application/download");
          header('Content-Disposition: attachment; filename="' . basename($file_real) . '"');
          header('Content-Length: ' . filesize($file_local));
          readfile($file_local);
          exit;
      }
  }
}

$ban_details = sql_get_ban_details($bid);

$activ_count = 0;
$ban_details_activ = sql_get_ban_details_activ($ban_details["player_id"], $activ_count, $bid);

$exp_count = 0;
$ban_details_exp = sql_get_ban_details_exp($ban_details["player_id"], $exp_count, $bid);

// Pobranie edytowanych banów
$pdo = getPDO();
$stmt = $pdo->prepare("SELECT * FROM {$config->db_prefix}_bans_edit WHERE bid = ?");
$stmt->execute([$bid]);
$ban_details_edits = $stmt->fetchAll();
$edit_count = count($ban_details_edits);

// Generowanie steamcomid
if (!empty($ban_details["player_id"])) {
  $ban_details["player_comid"] = GetFriendId($ban_details["player_id"]);
}

$smarty->assign("ban_detail", $ban_details);
$smarty->assign("ban_details_activ", $ban_details_activ);
$smarty->assign("ban_details_exp", $ban_details_exp);
$smarty->assign("ban_details_edits", $ban_details_edits);
$smarty->assign("edit_count", $edit_count);
$smarty->assign("activ_count", $activ_count);
$smarty->assign("exp_count", $exp_count);
$smarty->assign("type_output", ["SteamID", "SteamID & IP"]);
$smarty->assign("type_values", ["S", "SI"]);
$smarty->assign("site", $site);

// Get comments
$comments_count = 0;
$comments = sql_get_comments($bid, $comments_count);
$smarty->assign("comments", $comments);
$smarty->assign("comments_count", $comments_count);

// Get files
$files_count = 0;
$demos = sql_get_files($bid, $files_count);
$smarty->assign("demos", $demos);
$smarty->assign("demos_count", $files_count);

$smarty->assign("msg_banedit", $msg_banedit);
$smarty->assign("msg_demo", $msg_demo);
$smarty->assign("msg_comment", $msg_comment);
$smarty->assign("ajaxlist", $_GET["ajax"]);

?>