<?php session_start(); ?>
<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <title>Kontakt | BudgetApp</title>
    <link rel="stylesheet" href="projektide.css">
</head>
<body>

<?php require "header.php"; ?>

<main class="container">
    <h1>Kontakt</h1>

    <section>
        <p>
            Har du frågor eller feedback? Fyll i formuläret så återkommer vi.
        </p>
    </section>

    <section>
        <form>
            <label for="name">Namn</label>
            <input type="text" id="name" name="name" required>

            <label for="email">E-post</label>
            <input type="email" id="email" name="email" required>

            <label for="message">Meddelande</label>
            <textarea id="message" name="message" rows="5" required></textarea>

            <button type="submit">Skicka</button>
        </form>
    </section>
</main>

<?php require "footer.php"; ?>

</body>
</html>