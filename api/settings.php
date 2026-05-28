<?php
session_start();
require '../conf.php';
$uid = $_SESSION['user_id'];
$user = $mysqli->query("SELECT * FROM users WHERE id = '$uid'")->fetch_assoc();
$req = $_GET['fdsqd'];
$bol = $_GET['dsjsah'];
if ($req == "dvyz") {
    if ($bol == 'true') {
            $mysqli->query("UPDATE settings SET dvyhetapka = 1 WHERE id = '$uid'");
    } else {
        $mysqli->query("UPDATE settings SET dvyhetapka = 0 WHERE id = '$uid'");
    }

} elseif ($req == "logdsnpfsdj") {
      if ($bol == 'true') {
            $mysqli->query("UPDATE settings SET notif = 1 WHERE id = '$uid'");
    } else {
        $mysqli->query("UPDATE settings SET notif = 0 WHERE id = '$uid'");
    }
} elseif ($req == "loginidasdandadhdasg") {
      if ($bol == 'true') {
            $mysqli->query("UPDATE settings SET telegram = 1 WHERE id = '$uid'");
    } else {
        $mysqli->query("UPDATE settings SET telegram = 0 WHERE id = '$uid'");
    }
}
 echo "ok req: " . $req . ' bol: ' . $bol;