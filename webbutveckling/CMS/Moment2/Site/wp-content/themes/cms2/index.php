<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,600;0,700;0,800;1,300;1,400;1,600;1,700;1,800&display=swap"
          rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>">
    <link rel="icon" href="./media/logo.png" type="image/png">
    <title><?php bloginfo('name'); ?> | Hem</title>
</head>
<body>
<div class="home-background-wrapper">
    <img class="home-background" src="./media/homeBackground.jpg" alt="">
</div>
<header class="home-header">
    <a href="./" class="logo-nav">
        <span class="logo-text big-green">Skog AB</span>
        <img class="logo-image" src="./media/logo.svg" alt="company logo">
    </a>
    <nav id="main-nav" class="main-nav">
        <ul>
            <li><a class="big-green" href="./Nyheter/">Nyhter</a></li>
            <li><a class="big-green" href="./OmOss/">Om Oss</a></li>
            <li><a class="big-green" href="./Kontakt/">Kontakt</a></li>
        </ul>
    </nav>
    <div id="burger" class="burger">
        <div></div>
        <div></div>
        <div></div>
    </div>
</header>
<div class="home-main-wrapper">
    <main class="home-main">
        <ul>
            <li><a class="big-green" href="./Nyheter/">Nyhter</a></li>
            <li><a class="big-green" href="./OmOss/">Om Oss</a></li>
            <li><a class="big-green" href="./Kontakt/">Kontakt</a></li>
        </ul>
    </main>
</div>
<footer class="home-footer">
    <div><span class="big-green">Copyleft 🄯</span></div>
    <div><span class="big-green">Skogsföretagen AB</span></div>
    <div>
        <address class="big-green">Adressvägen 1, 234 56 Postkontoret</address>
    </div>
    <div><span class="big-green">Telefon: <a class="big-green" href="tel:+4612345678">012 34 56 78</a></span></div>
    <div><a class="big-green" href="./Kontakt/">Mail Oss</a></div>
</footer>
<script src="./static/main.js"></script>
</body>
</html>