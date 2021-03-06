<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <!-- Die 3 Meta-Tags oben *müssen* zuerst im head stehen; jeglicher sonstiger head-Inhalt muss *nach* diesen Tags kommen -->
  <title>Chateingang</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
  integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
  <!-- Eigene Styles -->
  <link rel="stylesheet" href="css/bootstrap-custom.css">
  <link rel="stylesheet" type="text/css" href="cssas.css">
  <!-- ACHTUNG: IE < 9 unterstützen wir nicht mehr! -->
</head>
<body style="background-color: #00cccc">

  <div class="container well" style="margin-top: 10%;">
    <center><h1>Memegalerie</h1></center>
    <form action="Bildergalerie.php" method="post" enctype="multipart/form-data">
      Select image to upload:
      <input type="file" name="fileToUpload" id="fileToUpload">
      <input type="submit" value="Upload Image" class="btn" name="submit">
    </form>

    <?php
    $target_dir = "img/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    // Check if image file is a actual image or fake image
    if(isset($_POST["submit"])) {
      $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
      if($check !== false) {
        $uploadOk = 1;
      } else {
        $uploadOk = 0;
      }
    }

    // Check if file already exists
    if (file_exists($target_file)) {
      $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["fileToUpload"]["size"] > 99900000) {
      $uploadOk = 0;
    }

    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
      $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
      // if everything is ok, try to upload file
    } else {
      if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
      } else {
      }
    }

    if (isset($_GET["pwd"])) {
      $datei = fopen("Account.txt","r");
      $zeile = true;
      $stimmt = false;
      while ($zeile) {
        $zeile = fgets($datei);

        if (password_verify($_GET["pwd"],$zeile)){
          $stimmt = true;
        }
      }
      if ($stimmt == false){
        header("Location:Login.php");
      }
      fclose($datei);
    }
    // Exif-Infos eines JPG-Bildes auslesen


    const IMAGE_HANDLERS = [
      IMAGETYPE_JPEG => [
        'load' => 'imagecreatefromjpeg',
        'save' => 'imagejpeg',
        'quality' => 100
      ],
      IMAGETYPE_PNG => [
        'load' => 'imagecreatefrompng',
        'save' => 'imagepng',
        'quality' => 0
      ],
      IMAGETYPE_GIF => [
        'load' => 'imagecreatefromgif',
        'save' => 'imagegif'
      ]
    ];
    function createThumbnail($src, $dest, $targetWidth, $targetHeight = null) {


      $type = exif_imagetype($src);

      // if no valid type or no handler found -> exit
      if (!$type || !IMAGE_HANDLERS[$type]) {
        return null;
      }

      // load the image with the correct loader
      $image = call_user_func(IMAGE_HANDLERS[$type]['load'], $src);

      // no image found at supplied location -> exit
      if (!$image) {
        return null;
      }

      // get original image width and height
      $width = imagesx($image);
      $height = imagesy($image);

      // maintain aspect ratio when no height set
      if ($targetHeight == null) {

        // get width to height ratio
        $ratio = $width / $height;

        // if is portrait
        // use ratio to scale height to fit in square
        if ($width > $height) {
          $targetHeight = floor($targetWidth / $ratio);
        }
        // if is landscape
        // use ratio to scale width to fit in square
        else {
          $targetHeight = $targetWidth;
          $targetWidth = floor($targetWidth * $ratio);
        }
      }

      // create duplicate image based on calculated target size
      $thumbnail = imagecreatetruecolor($targetWidth, $targetHeight);

      // set transparency options for GIFs and PNGs
      if ($type == IMAGETYPE_GIF || $type == IMAGETYPE_PNG) {

        // make image transparent
        imagecolortransparent(
          $thumbnail,
          imagecolorallocate($thumbnail, 0, 0, 0)
        );

        // additional settings for PNGs
        if ($type == IMAGETYPE_PNG) {
          imagealphablending($thumbnail, false);
          imagesavealpha($thumbnail, true);
        }
      }

      // copy entire source image to duplicate image and resize
      imagecopyresampled(
        $thumbnail,
        $image,
        0, 0, 0, 0,
        $targetWidth, $targetHeight,
        $width, $height
      );

      // 3. Save the $thumbnail to disk
      // - call the correct save method
      // - set the correct quality level

      // save the duplicate version of the image to disk
      return call_user_func(
        IMAGE_HANDLERS[$type]['save'],
        $thumbnail,
        $dest,
        IMAGE_HANDLERS[$type]['quality']
      );
    }

    //  Für die Thumbnails zum erschaffen
    $verzeichnis = 'img/';
    $thumbverzeichnis = 'img/';
    foreach (array_slice(scanDir($verzeichnis), 2) as $datei) {
      if(substr($datei, 0, 6) != 'Thumb_'){
        if (in_array(substr($datei, -3, 3), array('png'))) {
          $image = imagecreatefrompng($verzeichnis . $datei);
          $bg = imagecreatetruecolor(imagesx($image), imagesy($image));
          imagefill($bg, 0, 0, imagecolorallocate($bg, 255, 255, 255));
          imagealphablending($bg, TRUE);
          imagecopy($bg, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));
          imagedestroy($image);
          $quality = 100; // 0 = worst / smaller file, 100 = better / bigger file
          imagejpeg($bg, $verzeichnis . $datei . ".jpg", $quality);
          imagedestroy($bg);
        }
        if (in_array(substr($datei, -3, 3), array('gif'))) {
          $image = imagecreatefromgif($verzeichnis . $datei);
          $bg = imagecreatetruecolor(imagesx($image), imagesy($image));
          imagefill($bg, 0, 0, imagecolorallocate($bg, 255, 255, 255));
          imagealphablending($bg, TRUE);
          imagecopy($bg, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));
          imagedestroy($image);
          $quality = 100; // 0 = worst / smaller file, 100 = better / bigger file
          imagejpeg($bg, $verzeichnis . $datei . ".jpg", $quality);
          imagedestroy($bg);
        }
        if (in_array(substr($datei, -4, 4), array('j2peg'))) {
          if (file_exists($thumbverzeichnis . 'Thumb_' . $datei) == false) {
            createThumbnail($verzeichnis . $datei, $thumbverzeichnis . 'Thumb_' . $datei, 150);
          }
        }
        if (in_array(substr($datei, -3, 3), array('jpg'))) {
          if (file_exists($thumbverzeichnis . 'Thumb_' . $datei) == false) {
            createThumbnail($verzeichnis . $datei, $thumbverzeichnis . 'Thumb_' . $datei, 150);
          }
        }
      }
    }
    $verzeichnis = openDir("img");

    while ($file = readDir($verzeichnis)) {
      // Höhere Verzeichnisse nicht anzeigen!
      //createThumbnail($file, substr($file, 4)."Thumb_", 150);
      if ($file != "." && $file != ".." && substr($file, 0, 6) != 'Thumb_') {
        if (exif_imagetype("img/".$file) ==IMAGETYPE_JPEG) {
          echo"<div style='height: 200px; width: 200px; margin:20px; float:left;' >";
          echo "<a href=\"img/$file\"><img src=\"img/Thumb_".$file."\"></a>";
          $exif = exif_read_data('img/' . $file, 'FileName');
          if (isset($exif['Title'])) {
            echo "<p>Exif Title: " . $exif['Title']."<p>"; //Diese Funktion hat nur bei jpg Dateien funktioniert
          } else {
            echo "<p>Filename: " . $exif['FileName']."<p>";
          }
          if ($exif['FileSize'] > 1000000) {
            echo "Dateigrösse: " .round($exif['FileSize'] / 1000000,2)." MB";
          }
          else {
            echo "Dateigrösse: " .round($exif['FileSize'] / 1000,2)." kB";
          }
          echo("</div>");
        }
      }
    }
    // Verzeichnis schließen
    closeDir($verzeichnis);

    echo("</div>");

    ?>

  </div>
</div>
</body>
<!-- jQuery (wird für Bootstrap JavaScript-Plugins benötigt) -->
<script src="https://code.jquery.com/jquery-2.2.4.min.js"
integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
<!-- Binde alle kompilierten Plugins zusammen ein (wie hier unten) oder such dir einzelne Dateien nach Bedarf aus -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<!-- Validator -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.5/validator.min.js"
integrity="sha256-IxYUmOOk74FUrcx5FEMOHVmTJDb7ZAwnC/ivo/OQGxg=" crossorigin="anonymous"></script>
<!-- Eigene JavaScripts -->
</html>
