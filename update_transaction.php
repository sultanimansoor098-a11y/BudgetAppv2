<?php
session_start();
require "db.php";
require "db.php";

if (!isset($_SESSION["user_id"])) exit;

$id = (int)$_POST["id"];
$amount = (int)$_POST["amount"];
$category = trim($_POST["category"]);

if ($amount <= 0 || $category === "") {
  die("Ogiltig input.");
}

$stmt = $db->prepare("UPDATE transactions SET amount=?, category=? WHERE id=? AND user_id=?");
$stmt->execute([$amount,$category,$id,$_SESSION["user_id"]]);

header("Location: projektide.php");
exit;
