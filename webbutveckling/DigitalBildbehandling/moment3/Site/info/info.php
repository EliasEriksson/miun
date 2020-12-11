<!doctype html>
<html lang="sv">
<head>
    <meta charset="utf-8">
    <title>Samtliga bilder</title>
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
        body { font-family: Roboto, sans-serif;	font-size: 16px; line-height: 1.6em; background-color: #F3F3F3; color: #111111; }
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Raleway', sans-serif;
            color: rgb(36, 36, 36);
            font-variant: small-caps;
            letter-spacing: 4px;
            font-weight: bold;
        }
        h1 { font-size: 32px; }
        h2 { font-size: 24px; }
        h3 { font-size: 22px; }
        h4 { font-size: 20px; }
        .container { width: 96%; max-width: 1220px; margin: 0 auto; }
        .white-box { padding: 2%; background-color: #fff; margin: 3em auto 2em auto; border: 1px solid rgba(0,0,0,0.2); }
        .top-spacer {  }
        .image { clear: both; padding: 1em; border-top: 1px solid #ccc; margin: 1em 0 0 0; }
        .image:nth-child(1) { border: 0; }
        .image-info { float: left;  }
        .clear { clear: both; }
        .small-images { max-width: 100px; max-height: 100px; float: left; margin: 0 1em 1em 0; }
        .mid-images { max-width: 200px; max-height: 200px; float: left; margin: 0 1em 1em 0; }
		.large-images { max-width: 350px; max-height: 250px; float: left; margin: 0 1em 1em 0; }
        .video { max-width: 350px; float: left; margin: 0 1em 1em 0;}
        .summary { position: fixed; top: 0; left: 0; height: 40px; line-height: 40px; width: 100%; color: #000; text-align: right; text-align: center; font-size: 0.825em; background-color: #000; color: #fff; }
    </style>
</head>

<body>
<div id="allt">
    <div class="container top-spacer white-box">
        <header>
        	Navigering: &nbsp;<a href="../index.html">Startsidan</a>&nbsp;|&nbsp;<a href="info.php">Information</a>&nbsp;|&nbsp;<a href="info2.php">Information 2</a>
            <h1>Samtliga bilder</h1>
        </header>
        
        <?= GetAllFiles("../bilder/*.*"); ?>
        
        <?php
        /**
        * Returnerar info om bild utifrån filnamn
        * @param $filename, $class (vilken CSS-klass som ska läggas till bilden
        * @return string
        **/
        function GetFileInfo($filename, $class="mid-images", $long=true) {
            
            if(file_exists($filename)) {			
                // Samla information	
                // Filstorlek
                $filesize = filesize($filename);
                $filesize = round($filesize / 1000);
                
                // Mått
                list($width, $height, $type, $attr) = getimagesize($filename);
                
                // Typ
                // 1 = GIF, 2 = JPEG, 3 = PNG, 17 = ICO       
                if($type == 1) {
                    $type = "GIF";
                } else if($type == 2) {
                    $type = "JPEG";
                } else if($type == 3) {
                    $type = "PNG";
                } else if($type == 17) {
                    $type = "Ikon (ico)";
                } else {
                    if(substr($filename, -3) =="mp4"){
                        $type = "MP4";
                    } else if(substr($filename, -3) =="svg") {
                        $type = "SVG";
                    } else {
                        $type =  "Okänd";
                    }                    
                }
                
                // Ta bort sökväg från filnamnet
				$filename_short = basename($filename);
                
                // Returnera sträng redo för utskrift till skärm
                // Film eller bild?
                if($type == "MP4") {
                    $output = "<video controls class='video'><source src='$filename' type='video/mp4'></video>";
                } else {
                    $output = "<a href='$filename' rel='lightbox' title='$filename_short'><img src='$filename' alt='$filename_short' class='$class' /></a>\n";
                }                
                        
				if($long) {
					$output .= "<div class='image-info'><p><strong>Filnamn:</strong> $filename_short" 
                        . "<br>\n" 
						. "<strong>Typ:</strong> $type<br>\n<strong>Filstorlek:</strong> " . $filesize . "kB<br>\n<strong>Bredd:</strong> " .$width . "px<br>\n" ." <strong>Höjd:</strong> 			$height" . "px<br>\n"
                        . "</div>";
				} else {
					$output .= "<span class='shortdesc'>$filename_short, $filesize kB ($type)</span>";
				}            
				
				// Returnera färdig sträng
				return $output;
				             
            } else {
                return "<div class='image-info'>Filen $filename saknas.</div>\n";
            }
        }
        
        /**
        * Skriver ut alla filer utifrån en sökväg
        * @param $path
        * @return string
        **/
        function GetAllFiles($searchstring, $class="mid-images", $long=true) {
            $output = "";
            $numOfFiles = 0;
            $totalSize = 0;
            foreach (glob($searchstring) as $filename) {	
                // Hoppa över textfiler
                if(substr($filename, -3) == "txt") { continue; }

                $output .= "<div class='image'>" . GetFileInfo($filename, $class) . "</div>";	
                $numOfFiles++;
                $totalSize += round(filesize($filename) / 1000);
            }	
            
			$output .= "<div class='summary'><div class='container'>Sammanlagd filstorlek: $totalSize" . "kB - Antal filer: $numOfFiles</div></div>";
            return $output;
        }
        ?>
        
        <div class="clear spacer"></div>
    </div><!-- /.container -->
</div><!-- /#allt -->
</body>
</html>