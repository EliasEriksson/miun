<!doctype html>
<html lang="sv">

<head>
    <meta charset="utf-8">
    <title>Bilder utifrån uppgift</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <!-- Google fonts -->
    <link href="//fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
    <link href="//fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <!-- Lightbox -->
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/lightbox2/2.7.1/css/lightbox.css" type="text/css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/lightbox2/2.7.1/js/lightbox.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/lightbox2/2.7.1/js/lightbox.min.map"></script>
    <style>
        body {
            font-family: Roboto, sans-serif;
            font-size: 16px;
            line-height: 1.6em;
            background-color: #F3F3F3;
            color: #111111;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Raleway', sans-serif;
            color: rgb(36, 36, 36);
            font-variant: small-caps;
            letter-spacing: 4px;
            font-weight: bold;
        }

        h1 {
            font-size: 32px;
        }

        h2 {
            font-size: 24px;
        }

        h3 {
            font-size: 22px;
        }

        h4 {
            font-size: 20px;
        }

        .container {
            width: 96%;
            max-width: 1220px;
            margin: 0 auto;
        }

        .white-box {
            padding: 2%;
            background-color: #fff;
            margin: 3em auto 2em auto;
            border: 1px solid rgba(0, 0, 0, 0.2);
        }

        .image {
            clear: both;
            padding: 1em;
            border-top: 1px solid #ccc;
            margin: 1em 0 0 0;
        }

        .top-spacer {}

        .image:nth-child(1) {
            border: 0;
        }

        .image-info {
            float: left;
        }

        .small-images {
            max-width: 100px;
            margin: 1em 0 2px 0;
            display: block;
        }

        .mid-images {
            max-width: 200px;
            float: left;
            margin: 0 1em 1em 0;
        }

        .large-images {
            max-width: 350px;
            max-height: 200px;
            float: left;
            margin: 0 1em 1em 0;
        }

        .summary {
            position: absolute;
            top: 1em;
            left: 0;
            height: 2em;
            line-height: 2em;
            width: 100%;
            color: #000;
            text-align: right;
        }

        p:first-child {
            margin-top: 0;
        }

        h2 {
            padding-top: 1em;
            border-top: 1px solid #ccc;
            clear: both;
        }

        .video {
            display: block;
            width: 100%;
            margin-bottom: 10px;
        }

        .clear {
            clear: both;
        }

        .summary {
            position: fixed;
            top: 0;
            left: 0;
            height: 40px;
            line-height: 40px;
            width: 100%;
            color: #000;
            text-align: right;
            text-align: center;
            font-size: 0.825em;
            background-color: #000;
            color: #fff;
        }

        .error {
            font-weight: bold;
            color: red;
        }
    </style>
</head>

<body>
    <div id="allt">
        <div class="container top-spacer white-box">
            <header>
                Navigering: &nbsp;<a href="../index.html">Startsidan</a>&nbsp;|&nbsp;<a href="info.php">Information</a>&nbsp;|&nbsp;<a href="info2.php">Information 2</a>
                <h1>Bilder utifrån uppgift</h1>
                <p>Notering: <em>om höjd- eller bredd-mått är markerat med röd text kan det beror på att filen har fel storlek.</em></p>
            </header>

            <?= GetAllFiles("../bilder/*.*"); ?>

            <h2 style="border: 0;">Uppgift 1 - Sitemap</h2>
            <p>Du ska i denna uppgift göra en sitemap utifrån en webbplats av typen webbutik.<br>
                Du väljer själv vilken webbutik du vill använda att arbeta med
                - välj en du brukar använda eller du tycker är intressant på något sätt.<br>
                Denna sitemap ska visa hur webbsidorna hänger ihop, och hur huvud-navigeringen fungerar.<br>
                Gällande sitemaps för webbutiker är inte varje enskild produkt av intresse då varje produkts
                sida vanligtvis är likadant utformad för varje produkt.
                Det räcker att visa vägen fram till en enskild produkt för varje underkategori (om sådan finns).
            </p>
            <p>Filformat: jpg
                <br>
                Bildstorlek: 1200 x 750 px
                <br> Namn på bild: sitemap.jpg</p>

            <?= GetFileInfo("../bilder/sitemap.jpg", "large-images", true, 1200, 750); ?>
            <div class="clear"></div>

            <h2>Uppgift 2 - wireframe</h2>
            <p> Du ska i denna uppgift göra <b>wireframeskisser</b> utifrån webbplatsen i uppgiften ovan. Ta en
                skärmdump på hur själva webbsidan (startsidan) ser ut och utgå från den då du identifierar de
                olika segment webbsidan består av. <em>Logotyp/namn på webbplats ska framgå i skärmdumpen</em>.</p>
            <p>
                Du ska göra en wireframe över hur startsidan är sektionerad, både för stor skärm (desktop) och mindre
                (mobiltelefon), dels för startsidan och dels för hur sidan som visar varukorgen.
            </p>
            <p>
                Du ska skapa fem stycken bilder för denna uppgift - en skärmdump hur startsidan ser ut, samt wireframes
                för startsidan (desktop och mobil) och wireframes för varukorgen (desktop och mobil).
            </p>
            <p>Filformat: jpg
                <br>
                <ul>
                    <li>Bildstorlek (skärmdump): 1200 x 750 px - Filnamn: webbsida.jpg</li>
                    <li>Startsida: Bildstorlek (wireframe - desktop): 800 x 1000px - Filnamn: wireframe1.jpg</li>
                    <li>Startsida: Bildstorlek (wireframe - mobil): 300 x 1000px - Filnamn: wireframe2.jpg</li>
                    <li>Varukorg: Bildstorlek (wireframe - desktop): 800 x 1000px - Filnamn: wireframe3.jpg</li>
                    <li>Varukorg: Bildstorlek (wireframe - mobil): 300 x 1000px - Filnamn: wireframe4.jpg</li>
                </ul>
            </p>

            <?= GetFileInfo("../bilder/webbsida.jpg", "large-images", true, 1200, 750); ?>
            <div class="clear"></div>
            <?= GetFileInfo("../bilder/wireframe1.jpg", "large-images", true, 800, 1000); ?>
            <div class="clear"></div>
            <?= GetFileInfo("../bilder/wireframe2.jpg", "large-images", true, 300, 1000); ?>
            <div class="clear"></div>
            <?= GetFileInfo("../bilder/wireframe3.jpg", "large-images", true, 800, 1000); ?>
            <div class="clear"></div>
            <?= GetFileInfo("../bilder/wireframe4.jpg", "large-images", true, 300, 1000); ?>
            <div class="clear"></div>

            <h3>Uppgift 3 - moodboard</h3>
            <p>Ta webbplatsen och gör en <b>moodboard</b> till denna. Fånga känslan i de olika webbsidorna
                i färg och form.
                Ange vilka teckensnitt som används, samt vilka teckenstorlekar (grad) som används för brödtext och
                rubriker. Ange även färgkoder för dessa i din moodboard.</p>
            <p>Filformat: jpg
                <br>
                Bildstorlek: 1200 x 750 px
                <br> Namn på bild: moodboard.jpg</p>

            <?= GetFileInfo("../bilder/moodboard.jpg", "large-images", true, 1200, 750); ?>
            <div class="clear"></div>

            <h2>Uppgift 4 - bildspel</h2>
            <p> Du ska göra iordning 3 bilder till ett bildspel som ska vara på webbplatsens startsida. Bilderna ska vara valfria foton, men du får gärna ha fotat dem själv. Tänk på att bilderna ska vara vara så små som möjligt i kb. Extra viktigt då detta är tre bilder i toppen på en startsida.</p>
            <p>Filformat: jpg
                <br>
                Bildstorlek: 1200 x 300 px
                <br>
                Namn på bild: slideshow1.jpg, slideshow2.jpg och slideshow3.jpg</p>
            <?= GetFileInfo("../bilder/slideshow1.jpg", "large-images", true, 1200, 300); ?>
            <div class="clear"></div>
            <?= GetFileInfo("../bilder/slideshow2.jpg", "large-images", true, 1200, 300); ?>
            <div class="clear"></div>
            <?= GetFileInfo("../bilder/slideshow3.jpg", "large-images", true, 1200, 300); ?>
            <div class="clear"></div>

            <h2>Uppgift 5 - porträtt</h2>
            <p> Du ska ta en bild på dig själv och beskära till en kvadrat och rätt storlek. Porträttet ska också friläggas, dvs bakgrunden ska tas bort och vara genomskinlig. Därför används i denna uppgift png som format. Ett tips är att använda masker för friläggningen.</p>
            <p>Filformat: png
                <br> Bildstorlek: 400 x 400 px
                <br>
                Namn på bild: portratt.png</p>
            <?= GetFileInfo("../bilder/portratt.png", "large-images", true, 400, 400); ?>
            <div class="clear"></div>

            <h2>Uppgift 6 - friläggning med skugga</h2>
            <p> Här ska du frilägga en bild av tre föremål och lägga på en skugga. Tänk att det skulle kunna vara tre produkter i en webbshop. Och som i uppgiften ovan är tipset att använda masker.</p>
            <p>Filformat: png
                <br>
                Bildstorlek: 300 x 300 px
                <br>
                Namn på bild: frilaggning01.png, frilaggning02.png, frilaggning03.png </p>
            <?= GetFileInfo("../bilder/frilaggning01.png", "large-images", true, 300, 300); ?>
            <div class="clear"></div>
            <?= GetFileInfo("../bilder/frilaggning02.png", "large-images", true, 300, 300); ?>
            <div class="clear"></div>
            <?= GetFileInfo("../bilder/frilaggning03.png", "large-images", true, 300, 300); ?>
            <div class="clear"></div>

            <h2>Uppgift 7 - färgstick</h2>
            <p> Välj en egen bild där du ändrar färgbalansen i bilden. Det kan vara en del av bilden eller hela du ändrar på.
                Det du ska lägga in är en före-bild och en bild efter du ändrat färgbalansen.</p>
            <p>Filformat: jpg
                <br>
                Bildstorlek: 1200 x 750 px
                <br>
                Filnamn: fargstick.jpg, fargstick_fore.jpg</p>
            <?= GetFileInfo("../bilder/fargstick.jpg", "large-images", true, 1200, 750); ?>
            <div class="clear"></div>
            <?= GetFileInfo("../bilder/fargstick_fore.jpg", "large-images", true, 1200, 750); ?>
            <div class="clear"></div>

            <h2>Uppgift 8 - handkolorering och retusch</h2>
            <p>
                I denna uppgift ska du retuschera den <a href="https://elearn20.miun.se/moodle/pluginfile.php/859306/mod_resource/content/20/handkolorering.jpg" target="_blank">svartvita bilden</a> handkolorering.jpg och sen också färga olika delar i bilden så att det ser ut som ett färgfoto.
            </p>
            <p>Filformat: jpg
                <br>
                Bildstorlek: 1200 x 845 px
                <br>
                Namn på bild: handkolorering.jpg</p>
            <?= GetFileInfo("../bilder/handkolorering.jpg", "large-images", true, 1200, 845); ?>
            <div class="clear"></div>

            <h2>Uppgift 9 - logotyp</h2>
            <p> Här ska du göra en logotyp av ditt namn som ska sparas som en SVG-fil. </p>
            <p>Filformat: svg
                <br>
                Bildstorlek: 400 x 40 px
                (storleken är inte det viktiga gällande en vektoriserad SVG -
                huvudsaken att den har samma höjd- och bredd-förhållande som 400px x 40px,
                som är det mått den kommer visas i på en större skärm)<br> Namn på bild: logotyp.svg </p>

            <?= GetFileInfo("../bilder/logotyp.svg", "large-images"); ?>
            <div class="clear"></div>

            <h2>Uppgift 10 - favicon</h2>
            <p> Tillverka en favicon för webbsidan, i två stycken olika filformat. </p>
            <p>Filformat: ico, png
                <br> Bildstorlek: 16x16 px (ico), 32x32 px (png)
                <br> Namn på bild: favicon.ico, facicon.png </p>
            <?= GetFileInfo("../bilder/favicon.ico", "large-images", true, 16, 16); ?>
            <div class="clear"></div>
            <?= GetFileInfo("../bilder/favicon.png", "large-images", true, 32, 32); ?>
            <div class="clear"></div>

            <h2>Uppgift 11 - upprepad bakgrund</h2>
            <p> Gör en bild som upprepar sig i bakgrunden på hemsidan (själva upprepningen är inbyggt i koden för webbsidan) utan att
                några skarvar av bilden syns. </p>
            <p>Filformat: jpg <br>
                Bildstorlek: valfritt, men inte vara stor i kb<br>
                Namn på bild: upprepadbakgrund.jpg</p>
            <?= GetFileInfo("../bilder/upprepadbakgrund.jpg", "large-images", true, 0, 0); ?>
            <div class="clear"></div>

            <h2>Uppgift 12 - reklambanner GIF</h2>
            <p>Du ska skapa en reklambanner för undersidan för webbdesign-sidan här i labb 3. Bannern ska vara en animerad gif-bild. </p>
            <p>Filformat: gif
                <br> Bildstorlek: 250 x 360 px
                <br> Namn på bild: banner.gif
            </p>
            <?= GetFileInfo("../bilder/banner.gif", "large-images", true, 250, 360); ?>
            <div class="clear"></div>

            <h2>Uppgift 13 - bakgrundsfilm mp4</h2>
            <p>Här ska du skapa en videofilm som ska ligga i bakgrunden på webbsidan. Spela in en egen film på valfritt sätt, till exempel
                med mobilen, en videokamera, DSLR-kamera etc. Använd en lämplig codec som fungerar i de flesta moderna webbläsare. Ta också bort ljudet.</p>

            <p>Filformat: mp4
                <br>
                Bildstorlek: 1200 x 300 px (håll inom rimlig filstorlek).
                <br>Namn på film: bakgrundsfilm.mp4</p>

            <?= GetFileInfo("../bilder/bakgrundsfilm.mp4", "large-images", true, 1200, 300); ?>
            <div class="clear"></div>

            <?php
            /**
             * Returnerar info om bild utifrån filnamn
             * @param $filename
             * @param $class (vilken CSS-klass som ska läggas till bilden
             * @param $long - kort eller lång info (om höjd/bredd ska visas) - standard: lång
             * @param $correctwidth - facit-bredd
             * @param $correctheight - facit-höjd
             * @param 
             * @return string
             **/
            function GetFileInfo($filename, $class = "mid-images", $long = true, $correctwidth = 0, $correctheight = 0)
            {

                if (file_exists($filename)) {
                    // Samla information	
                    // Filstorlek
                    $filesize = filesize($filename);
                    $filesize = round($filesize / 1000);

                    // Mått
                    list($width, $height, $type, $attr) = getimagesize($filename);

                    // Typ
                    // 1 = GIF, 2 = JPEG, 3 = PNG
                    if ($type == 1) {
                        $type = "GIF";
                    } else if ($type == 2) {
                        $type = "JPEG";
                    } else if ($type == 3) {
                        $type = "PNG";
                    } else if ($type == 17) {
                        $type = "Ikon (ico)";
                    } else {
                        if (substr($filename, -3) == "mp4") {
                            $type = "MP4";
                        } else if (substr($filename, -3) == "svg") {
                            $type = "SVG";
                        } else {
                            $type =  "Okänd";
                        }
                    }

                    // Ta bort sökväg från filnamnet
                    $filename_short = basename($filename);

                    // Returnera sträng redo för utskrift till skärm
                    if ($type == "MP4") {
                        $output = "<video controls class='video'><source src='$filename' type='video/mp4'></video>\n";
                    } else {
                        $output = "<a href='$filename' rel='lightbox' title='$filename_short'><img src='$filename' alt='$filename_short' class='$class' /></a>\n";
                    }

                    if ($long) {

                        // Kontrollera om höjd/bredd = facit
                        $widthstr = $width;
                        $heightstr = $height;

                        if ($correctheight != 0 && $correctwidth != 0) {
                            if ($correctwidth != $width) {
                                $widthstr = "<span class='error'>" . $width . "</span>";
                            }

                            if ($correctheight != $height) {
                                $heightstr = "<span class='error'>" . $height . "</span>";
                            }
                        }

                        $output .= "<div class='image-info'><p><strong>Filnamn:</strong> $filename_short"
                            . "<br>\n"
                            . "<strong>Typ:</strong> $type<br>\n<strong>Filstorlek:</strong> " . $filesize . "kB<br>\n"
                            . "<strong>Bredd:</strong> " . $widthstr . " px<br>\n"
                            . " <strong>Höjd:</strong> " . $heightstr . " px<br>\n"
                            . "</div>";
                    } else {
                        $output .= "<span class='shortdesc'>$filename_short, $filesize kB ($type)</span>";
                    }

                    // Returnera färdig sträng
                    return $output;
                } else {
                    return "<div class='image-info error'>Filen $filename saknas.</div>\n";
                }
            }

            /**
             * Skriver ut alla filer utifrån en sökväg
             * @param $path
             * @return string
             **/
            function GetAllFiles($searchstring, $class = "mid-images", $long = true)
            {
                $output = "";
                $numOfFiles = 0;
                $totalSize = 0;
                foreach (glob($searchstring) as $filename) {
                    $numOfFiles++;
                    $totalSize += round(filesize($filename) / 1000);
                }

                $output = "<div class='summary'><div class='container'>Sammanlagd filstorlek: $totalSize" . "kB - Antal filer: $numOfFiles</div></div>";
                return $output;
            }
            ?>

            <div class="clear spacer"></div>
        </div><!-- /.container -->
    </div><!-- /#allt -->
</body>

</html>