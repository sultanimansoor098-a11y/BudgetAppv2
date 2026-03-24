<?php
// Startar sessionen så vi kan använda $_SESSION
session_start();
require "db.php";
require "db.php";

// Om ingen user_id finns i sessionen skickas användaren till login-sidan
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}
// - type måste vara "income" eller "expense"
$userId = (int)$_SESSION["user_id"];
$type = $_POST["type"] ?? "";
$amount = (int)($_POST["amount"] ?? 0);
$category = trim($_POST["category"] ?? "");

// - type måste vara "income" eller "expense"
if (!in_array($type, ["income","expense"]) || $amount <= 0 || $category === "") {
    header("Location: projektide.php");
    exit;
}

/* Lägg till transaktion */
// Förbereder en SQL-sats (prepared statement för säkerhet mot SQL-injection)
$stmt = $db->prepare("
    INSERT INTO transactions (user_id, type, amount, category)
    VALUES (?, ?, ?, ?)
");
$stmt->execute([$userId, $type, $amount, $category]);

/*  POÄNG & STREAK  */

$today = date("Y-m-d");
// Hämtar användarens nuvarande poäng, streak och senaste aktivitetsdatum
$stmt = $db->prepare("SELECT points, streak, last_action_date FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$points = (int)$user["points"] + 10;
$streak = (int)$user["streak"];
$last = $user["last_action_date"];

$yesterday = date("Y-m-d", strtotime("-1 day"));

if ($last === $today) {
    // inget
} elseif ($last === $yesterday) {
    $streak += 1;
} else {
    $streak = 1;
}
// Uppdaterar användarens poäng, streak och senaste aktivitetsdatum i databasen
$stmt = $db->prepare("
    UPDATE users 
    SET points = ?, streak = ?, last_action_date = ?
    WHERE id = ?
");
$stmt->execute([$points, $streak, $today, $userId]);

header("Location: projektide.php");
exit;
