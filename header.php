<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$current = basename($_SERVER["PHP_SELF"]);
?>

<header>
    <nav class="top-nav">
        <div class="logo">BudgetApp V2</div>

        <div class="nav-links">
            <a href="index.php" class="<?= $current === "index.php" ? "aktiv" : "" ?>">Hem</a>
            <a href="projektide.php" class="<?= $current === "projektide.php" ? "aktiv" : "" ?>">Budget</a>
            <a href="om-oss.php" class="<?= $current === "om-oss.php" ? "aktiv" : "" ?>">Om oss</a>
            <a href="kontakt.php" class="<?= $current === "kontakt.php" ? "aktiv" : "" ?>">Kontakt</a>

            <?php if (!empty($_SESSION["user_id"])): ?>
                <a href="logout.php" class="cta">Logga ut</a>
            <?php else: ?>
                <a href="login.php" class="<?= $current === "login.php" ? "aktiv" : "" ?>">Logga in</a>
            <?php endif; ?>
        </div>
    </nav>
</header>