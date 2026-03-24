<?php
session_start(); // Startar session (behövs för inloggning)
require "db.php"; // Kopplar till databasen
require "db.php";

/*  SKYDDA SIDAN  */
// Om användaren inte är inloggad → skicka till login-sidan
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit; // Stoppar resten av koden
}

$userId = (int)$_SESSION["user_id"]; // Hämtar användarens ID
$email = $_SESSION["email"];         // Hämtar användarens e-post


/*  HÄMTA ANVÄNDARE  */
// Hämtar sparmål, poäng och streak från databasen
$stmt = $db->prepare("
    SELECT savings_goal, points, streak, last_action_date
    FROM users
    WHERE id = ?
");
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC); // Sparar resultatet i en array


/*  SPARA SPARMÅL */
// Om användaren skickar formuläret för sparmål
if (isset($_POST["save_goal"])) {

    $goal = (int)$_POST["goal"]; // Gör om input till heltal

    if ($goal > 0) {

        // Uppdaterar sparmålet i users-tabellen
        $stmt = $db->prepare("
            UPDATE users
            SET savings_goal = ?
            WHERE id = ?
        ");
        $stmt->execute([$goal, $userId]);

        // Laddar om sidan för att undvika dubbelpostning
        header("Location: projektide.php");
        exit;
    }
}


/*  HÄMTA TRANSAKTIONER  */
// Hämtar alla användarens transaktioner (nyaste först)
$stmt = $db->prepare("
    SELECT id, type, amount, category, created_at
    FROM transactions
    WHERE user_id = ?
    ORDER BY id DESC
");
$stmt->execute([$userId]);
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);


/*  BERÄKNA SALDO  */
$saldo = 0;        // Här sparas totala saldot
$expenseData = []; // Här sparas utgifter per kategori (för diagrammet)

foreach ($transactions as $t) {

    if ($t["type"] === "income") {
        // Om det är en inkomst → lägg till beloppet
        $saldo += (int)$t["amount"];

    } else {
        // Om det är en utgift → dra bort beloppet
        $saldo -= (int)$t["amount"];

        // Samlar ihop utgifter per kategori
        $expenseData[$t["category"]] =
            ($expenseData[$t["category"]] ?? 0) + (int)$t["amount"];
    }
}


/* ===== RÄKNA UT PROGRESS ===== */
$progress = 0;

if ($user["savings_goal"] > 0) {
    // Räknar hur många % av sparmålet som uppnåtts
    $progress = min(100, ($saldo / $user["savings_goal"]) * 100);
}


/* ===== REWARD ===== */
$reward = false;

// Om saldot är större än eller lika med sparmålet → visa popup
if ($user["savings_goal"] > 0 && $saldo >= $user["savings_goal"]) {
    $reward = true;
}
?>

<!DOCTYPE html>
<html lang="sv">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Min budget | BudgetApp</title>
<link rel="stylesheet" href="projektide.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<header>
<nav class="top-nav">
  <div class="logo">BudgetApp</div>
  <div class="nav-links">
    <a href="index.php">Hem</a>
    <a href="projektide.php" class="aktiv">Budget</a>
    <a href="om-oss.php">Om oss</a>
    <a href="kontakt.php">Kontakt</a>
    <a href="logout.php" class="cta">Logga ut</a>
  </div>
</nav>
</header>

<main class="container">

<h1>Min budget</h1>
<p>
Inloggad som: <strong><?= htmlspecialchars($email) ?></strong><br>
⭐ Poäng: <strong><?= $user["points"] ?></strong> |
🔥 Streak: <strong><?= $user["streak"] ?></strong> dagar
</p>

<!-- ===== SPARMÅL ===== -->
<section>
<h2>Sparmål</h2>

<form method="post">
<label for="goal">Ange sparmål (kr)</label>
<input type="number"
       name="goal"
       id="goal"
       value="<?= (int)$user["savings_goal"] ?>"
       required>
<button type="submit" name="save_goal">Spara mål</button>
</form>

<?php if ($user["savings_goal"] > 0): ?>
<div class="progress-bar">
  <div class="progress-fill"
       style="width: <?= $progress ?>%;"></div>
</div>
<p><?= $saldo ?> kr av <?= $user["savings_goal"] ?> kr</p>
<?php endif; ?>
</section>

<!-- ===== TRANSAKTION ===== -->
<section>
<h2>Lägg till transaktion</h2>

<form method="post" action="add_transaction.php">
<select name="type" required>
<option value="income">Inkomst</option>
<option value="expense">Utgift</option>
</select>

<input type="number" name="amount" placeholder="Belopp" required>
<input type="text" name="category" placeholder="Kategori" required>
<button type="submit">Spara</button>
</form>
</section>

<!-- ===== HISTORIK ===== -->
<section>
<h2>Historik</h2>

<?php if (!$transactions): ?>
<p>Inga transaktioner ännu.</p>
<?php else: ?>

<ul class="tx-list">
<?php foreach ($transactions as $t): ?>
<li>

  <div>
    <?= htmlspecialchars($t["category"]) ?>
    <div class="tx-date">
      <?= substr($t["created_at"], 0, 10) ?>
    </div>
  </div>

  <div class="tx-right">
    <span class="<?= $t["type"] === "income" ? "income" : "expense" ?>">
      <?= $t["type"] === "income" ? "+" : "-" ?>
      <?= (int)$t["amount"] ?> kr
    </span>

    <form method="post"
          action="delete_transaction.php"
          class="inline-form"
          onsubmit="return confirm('Radera transaktionen?');">

      <input type="hidden"
             name="id"
             value="<?= (int)$t["id"] ?>">

      <button type="submit" class="danger">
        Radera
      </button>
    </form>
  </div>

</li>
<?php endforeach; ?>
</ul>

<?php endif; ?>

<p class="summary">Totalt saldo: <?= $saldo ?> kr</p>
</section>

<!-- ===== DIAGRAM ===== -->
<section>
<h2>Utgifter per kategori</h2>
<canvas id="expenseChart"></canvas>
</section>

</main>

<!-- ===== PREMIUM REWARD POPUP ===== -->
<?php if ($reward): ?>
<div class="reward-overlay" id="reward">
  <div class="reward-box">
    <div class="reward-icon">🏆</div>
    <h2>Grattis!</h2>
    <p>Du har nått ditt sparmål 🎉</p>
    <button onclick="closeReward()">Fortsätt</button>
  </div>
</div>
<?php endif; ?>

<script>
function closeReward() {
  const reward = document.getElementById("reward");
  reward.style.animation = "fadeOut 0.3s ease";
  setTimeout(() => reward.style.display = "none", 300);
}

/* ===== DIAGRAM ===== */
const expenseData = <?= json_encode($expenseData) ?>;

new Chart(document.getElementById("expenseChart"), {
  type: "pie",
  data: {
    labels: Object.keys(expenseData),
    datasets: [{
      data: Object.values(expenseData),
      backgroundColor: [
        "#3366CC","#DC3912","#FF9900","#109618",
        "#990099","#0099C6","#DD4477","#66AA00"
      ]
    }]
  },
  options: {
    responsive: true,
    plugins: {
      legend: { position: "bottom" }
    }
  }
});
</script>

</body>
</html>
