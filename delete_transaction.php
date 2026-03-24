<?php
session_start();
require "db.php";
require "db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$userId = (int)$_SESSION["user_id"];
$id = (int)($_POST["id"] ?? 0);

if ($id > 0) {
    $stmt = $db->prepare("
        DELETE FROM transactions
        WHERE id = ? AND user_id = ?
    ");
    $stmt->execute([$id, $userId]);
}

header("Location: projektide.php");
exit;
