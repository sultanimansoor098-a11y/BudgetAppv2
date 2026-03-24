<?php
session_start();
?>
<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Om oss | BudgetApp</title>
    <link rel="stylesheet" href="projektide.css">
</head>
<body>

<?php require "header.php"; ?>

<main class="container">
    <h1>Om BudgetApp</h1>

    <section>
        <h2>Vårt syfte</h2>
        <p>
            BudgetApp är utvecklad som en del av vårt gymnasiearbete inom webbutveckling.
            Syftet med projektet är att hjälpa studenter att få bättre kontroll över sin ekonomi.
            Många studenter har begränsad inkomst och flera små utgifter som snabbt kan bli stora summor.
            Genom att visualisera inkomster, utgifter och sparmål vill vi göra det enklare att förstå
            sin ekonomi och fatta smartare beslut.
        </p>
    </section>

    <section>
        <h2>Varför vi gör detta</h2>
        <p>
            Vi har valt att skapa BudgetApp eftersom ekonomi är en viktig del av vuxenlivet,
            men något som många unga upplever som svårt och otydligt.
            Vårt mål är att göra sparande mer motiverande och mindre tråkigt genom att
            kombinera traditionell budgethantering med ett belöningssystem.
            Genom tydlig feedback, poäng och visuella effekter vill vi inspirera
            studenter att spara pengar och bygga goda ekonomiska vanor.
        </p>
    </section>

    <section>
        <h2>Version 1 – Standardbudget</h2>
        <p>
            I den första versionen av BudgetApp fokuserade vi på grundläggande funktionalitet.
            Användaren kan registrera inkomster och utgifter, se sitt totala saldo
            och få en översikt över utgifter per kategori genom ett diagram.
            Denna version innehåller de viktigaste delarna av en traditionell budget:
        </p>

        <ul>
            <li>Inloggning med säkert lagrade lösenord</li>
            <li>Skapa, visa och radera transaktioner</li>
            <li>Dynamisk beräkning av saldo</li>
            <li>Visuellt diagram över utgifter</li>
        </ul>
    </section>

    <section>
        <h2>Version 2 – Belöningssystem</h2>
        <p>
            I version 2 har vi vidareutvecklat applikationen genom att lägga till
            ett belöningssystem inspirerat av gamification.
            Här kan användaren sätta ett personligt sparmål och följa sin utveckling
            genom en progressbar.
        </p>

        <p>
            När användaren registrerar transaktioner tjänar man poäng.
            Genom att använda appen flera dagar i rad byggs en så kallad “streak”.
            När sparmålet uppnås visas en visuell belöning på skärmen.
            Detta gör att sparandet känns mer engagerande och motiverande.
        </p>

        <ul>
            <li>Personligt sparmål</li>
            <li>Progressbar som visar utveckling</li>
            <li>Poängsystem</li>
            <li>Streak-funktion</li>
            <li>Visuell reward-animation vid uppnått mål</li>
        </ul>
    </section>

    <section>
        <h2>Teknik och programmeringsspråk</h2>
        <p>
            BudgetApp är utvecklad med moderna webbtekniker:
        </p>

        <ul>
            <li><strong>HTML5</strong> – För struktur och semantisk uppbyggnad av sidorna</li>
            <li><strong>CSS3</strong> – För layout, design, responsivitet och animationer</li>
            <li><strong>PHP</strong> – För serverlogik och dynamiskt innehåll</li>
            <li><strong>SQLite</strong> – För lagring av användare och transaktioner i databasen</li>
            <li><strong>Chart.js</strong> – För att skapa diagram över utgifter</li>
        </ul>

        <p>
            All databasåtkomst sker med förberedda frågor (prepared statements)
            för att minska risken för SQL-injektion.
            Webbplatsen är responsiv och anpassad för mobil, surfplatta och dator.
        </p>
    </section>

    <section>
        <h2>Sammanfattning</h2>
        <p>
            BudgetApp kombinerar traditionell budgethantering med ett motiverande belöningssystem.
            Genom att visa tydlig statistik, ge direkt feedback och skapa visuella belöningar
            vill vi göra ekonomisk planering enklare och mer inspirerande för studenter.
            Projektet visar hur webbteknik och programmering kan användas
            för att lösa ett verkligt problem i vardagen.
        </p>
    </section>
</main>

<?php require "footer.php"; ?>

</body>
</html>