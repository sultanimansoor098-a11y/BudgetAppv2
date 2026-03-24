<?php
session_start();
require "db.php";

if (isset($_SESSION["user_id"])) {
  header("Location: projektide.php");
  exit;
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

  $email = trim($_POST["email"] ?? "");
  $password = trim($_POST["password"] ?? "");

  if ($email === "" || $password === "") {
    $message = "Fyll i alla fält.";
  }

  if (isset($_POST["register"]) && $message === "") {

    $hash = password_hash($password, PASSWORD_DEFAULT);

    try {
      $stmt = $db->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
      $stmt->execute([$email, $hash]);

      $userId = $db->lastInsertId();

      $db->prepare("INSERT INTO goals (user_id) VALUES (?)")
         ->execute([$userId]);

      $db->prepare("INSERT INTO rewards (user_id) VALUES (?)")
         ->execute([$userId]);

      $message = "Konto skapat!";
    } catch (PDOException $e) {
      $message = "Användaren finns redan.";
    }
  }

  if (isset($_POST["login"]) && $message === "") {

    $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user["password"])) {
      $_SESSION["user_id"] = $user["id"];
      $_SESSION["email"] = $user["email"];
      header("Location: projektide.php");
      exit;
    } else {
      $message = "Fel e-post eller lösenord.";
    }
  }
}
?>
<!DOCTYPE html>
<html lang="sv">
<head>
<meta charset="UTF-8">
<title>Logga in</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<?php require "header.php"; ?>
<main class="container">
<h1>Logga in</h1>

<form method="post">
<label for="email">E-post</label>
<input type="email" name="email" id="email" required>

<label for="password">Lösenord</label>
<input type="password" name="password" id="password" required>

<button name="login">Logga in</button>
<button name="register" class="secondary">Registrera</button>
</form>

<p class="msg"><?= htmlspecialchars($message) ?></p>

</main>
<?php require "footer.php"; ?>
</body>
</html>
