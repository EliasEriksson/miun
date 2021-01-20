<?php
$rootURL = "./";
?>

<!doctype html>
<html lang="en">
<head>
    <?php
    include "templates/head.php";
    ?>
    <title>Moment 1 - Home</title>
</head>
<body>
<?php
include "templates/header.php";
?>

<main>
    <ol class="qna">
        <li>
            <p>Har du tidigare erfarenhet av utveckling med PHP?</p>
            <p>
                Jag har ingen större tidigare erfarenhet att skriva PHP men jag har suttit med ramverket django
                för python tidigare där jag bland annat gjort en <a href="http://eliaseriksson.eu/">chatt app</a>.
            </p>
        </li>
        <li>
            <p>
                Beskriv kortfattat vad du upplever är fördelarna med att använda PHP för att skapa webbplatser.
            </p>
            <p>
                Fördelarna är helt klar att sidan som byggs blir lättare att underhålla och smidigare att jobba med
                då samma kod kan återanvändas flera gånger och HTML struktur kana genereras från innehåll i databaser.
            </p>
        </li>
        <li>
            <p>
                Hur har du valt att strukturera upp dina filer och kataloger?
            </p>
            <p>
                Jag har valt att gå med samma struktur som ramverket django använder sig av då det är det
                jag är van att jobba med och det blir lätt att navigera med den.
                Strukturen bygger att ha alla phpfiler som inkluderas i en undermapp <span class="code">templates</span>
                med undermapp till undersida om det behövs.
                Alla CSS filer samt JavaScript ligger i en mapp på sidan rot som heter <span class="code">static</span>
                med underkatalogerna <span class="code">css</span> respektive <span class="code">js</span>.
                Annan php funktionarlitet som funktioner, klasser, config etc ligger i en mapp som heter
                <span class="code">utils</span>.
            </p>
        </li>
        <li>
            <p>
                Har du följt guiden, eller skapat på egen hand?
            </p>
            <p>
                Jag har inte följt guiden. Jag tycker inte om att bryta html taggarna för att sedan behöva komma ihåg
                vilka filer som kan lappa ihop andra. Jag har valt att flytta ner lite mer struktur i filerna
                själva för att får det lite mer lättläst. Taggarna jag valt att behålla är <span class="code">doctype</span>
                <span class="code">html</span> <span class="code">head</span> och <span class="code">body</span>.
            </p>
        </li>
        <li>
            <p>Har du gjort några förbättringar eller vidareutvecklingar av guiden (om du följt denna)?</p>
            <p>
                Jag gjorde helt om så Nej.
            </p>
        </li>
        <li>
            <p>
                Vilken utvecklingsmiljö har du använt för uppgiften (Editor, webbserver (XAMPP, LAMP, MAMP eller liknande) etcetera)?
            </p>
            <p>
                Jag sitter på ett linux system och har installerat mjukvaran manuellt och använder Apache, mariadb
                (som inte används till denna uppgift i och för sig) och php7.4.
                Jag använder PHPStorm som editor då jag alltid använd jetbrains editors coh tycker mycket om dom.
            </p>
        </li>
        <li>
            <p>
                Har något varit svårt med denna uppgift?
            </p>
            <p>
                Har inte haft något större strul hittils.
            </p>
        </li>
    </ol>
</main>

<?php
include "templates/footer.php";
?>
</body>
</html>