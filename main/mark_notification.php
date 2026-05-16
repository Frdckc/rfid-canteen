<?php
include_once('connect.php');

if(isset($_GET['id'])) {
    $notif_id = $_GET['id'];
    $update = $db->prepare("UPDATE tbl_notifications SET is_read=1 WHERE id=?");
    $update->execute([$notif_id]);
}

header('Location: ' . $_SERVER['HTTP_REFERER']);
exit();
?>