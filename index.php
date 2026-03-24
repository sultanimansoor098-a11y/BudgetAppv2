<?php
session_start();
require "db.php";

$saldo = 0;
$preview = [];

if (!empty($_SESSION["user_id"])) {
    $stmt = $db->prepare("
        SELECT type, amount, category
        FROM transactions
        WHERE user_id = ?
        ORDER BY id DESC
    ");
    $stmt->execute([$_SESSION["user_id"]]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($rows as $r) {
        $saldo += ($r["type"] === "income")
            ? (int)$r["amount"]
            : -(int)$r["amount"];
    }

    $preview = array_slice($rows, 0, 3);
}
?>

<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <title>BudgetApp – Hem</title>
    <link rel="stylesheet" href="projektide.css">
</head>
<body>

<?php require "header.php"; ?>

<main>
    <section class="hero">
        <div class="hero-content">
            <h1>Smart ekonomi för studenter</h1>

            <p>
                Få koll på inkomster, utgifter och sparmål.
                Version 2 har belöningar som gör sparandet roligare.
            </p>

            <div class="hero-buttons">
                <a href="projektide.php" class="primary-btn">Öppna budget</a>
                <a href="kontakt.php" class="secondary-btn">Kontakta oss</a>
            </div>
        </div>

        <aside class="hero-card">
            <p class="card-title">Totalt saldo</p>
            <h2><?= $saldo ?> kr</h2>

            <?php if (!empty($preview)): ?>
                <?php foreach ($preview as $t): ?>
                    <div class="transaction">
                        <span><?= htmlspecialchars($t["category"]) ?></span>

                        <span class="<?= $t["type"] ?>">
                            <?= $t["type"] === "income" ? "+" : "-" ?>
                            <?= (int)$t["amount"] ?> kr
                        </span>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="muted">Logga in och lägg till transaktioner.</p>
            <?php endif; ?>
        </aside>
    </section>
</main>

<?php require "footer.php"; ?>

</body>
</html>