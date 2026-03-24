<?php
session_start();
require "db.php";
require "db.php";

if (!isset($_SESSION["user_id"])) {
  header("Location: login.php");
  exit;
}

$goal = (int)($_POST["goal"] ?? 0);

if ($goal < 0) {
  die("Ogiltigt sparmål.");
}

$stmt = $db->prepare("
  UPDATE goals
  SET goal_amount = ?
  WHERE user_id = ?
");

$stmt->execute([$goal, $_SESSION["user_id"]]);

header("Location: projektide.php");
exit;
