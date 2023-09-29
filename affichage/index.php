<?php


$zip = isset($_GET['zip']);
$bmp = isset($_GET['bmp']);

$check = isset($_GET['check']);

$content = file_get_contents('https://lamourfood.fr/wp-json/custom/v1/menu-recap');
$menu = json_decode($content, true);
$hash = md5($content);

if ($check) {
    echo $hash;
    exit;
}
$current = $menu['current'] ?? false;
// Create a blank png image
$width = 1200;
$height = 825;
$image = imagecreatetruecolor($width, $height);

// // Set background to black
$black = imagecolorallocate($image, 0, 0, 0);
$white = imagecolorallocate($image, 255, 255, 255);
$textColor = $black;

imagefill($image, 0, 0, $white);

if ($current['illustration'] ?? false) {
    $cover = imagecreatefromstring(file_get_contents($current['illustration']));
    imagefilter($cover, IMG_FILTER_GRAYSCALE);
    $coverWidth = imagesx($cover);
    $coverHeight = imagesy($cover);

    // After loading the cover image and before the resizing
    $transparentImage = imagecreatetruecolor($coverWidth, $coverHeight);

    // Preserve transparency
    imagealphablending($transparentImage, false);
    imagesavealpha($transparentImage, true);

    for ($x = 0; $x < $coverWidth; $x++) {
        for ($y = 0; $y < $coverHeight; $y++) {
            $color = imagecolorat($cover, $x, $y);
            $alpha = ($color & 0x7F000000) >> 24;
            $alpha = intval($alpha + (127 * 0.8)); // 50% transparent
            $alpha = min(127, $alpha); // Ensure valid alpha

            $newColor = ($color & 0xFFFFFF) | ($alpha << 24);
            imagesetpixel($transparentImage, $x, $y, $newColor);
        }
    }

    // Now you replace the original cover image with the transparent one
    imagedestroy($cover);
    $cover = $transparentImage;

    // Calculate the ratios
    $ratioTarget = $width / $height;
    $ratioCover = $coverWidth / $coverHeight;

    if ($ratioCover > $ratioTarget) {
        // The cover image is wider in proportion to the target image
        $newCoverHeight = $height;
        $newCoverWidth = $height * $ratioCover;
        $srcX = ($newCoverWidth - $width) * 0.5 / ($newCoverWidth / $coverWidth);
        $srcY = 0;
    } else {
        // The cover image is taller in proportion to the target image
        $newCoverWidth = $width;
        $newCoverHeight = $width / $ratioCover;
        $srcY = ($newCoverHeight - $height) * 0.5 / ($newCoverHeight / $coverHeight);
        $srcX = 0;
    }

    // Place the cover image onto the main image
    imagecopyresampled($image, $cover, 0, 0, $srcX, $srcY, $newCoverWidth, $newCoverHeight, $coverWidth, $coverHeight);

    imagedestroy($cover); // Destroy the cover image after use
} else {

    // Load the bg.png and set it as the background
    // $bg = imagecreatefrompng('bg.png');
    // imagecopyresampled($image, $bg, 0, 0, 0, 0, $width, $height, imagesx($bg), imagesy($bg));
    // imagedestroy($bg);
}
// Define margin
$margin = $height * 0.10;
$lineJump = 30;
$marginMoyen = $height * 0.1;
$marginSmall = $height * 0.01;

// Load the logo.png and get its dimensions
$logo = imagecreatefrompng('logo.png');
imagesavealpha($logo, true);
$logoWidth = imagesx($logo);
$logoHeight = imagesy($logo);


// Loop through each pixel and set its color to black
for ($y = 0; $y < $logoHeight; $y++) {
    for ($x = 0; $x < $logoWidth; $x++) {
        $rgba = imagecolorat($logo, $x, $y);
        $alpha = ($rgba & 0x7F000000) >> 24;
        $b = imagecolorallocatealpha($logo, 0, 0, 0, $alpha);
        imagesetpixel($logo, $x, $y, $b);
    }
}


// Calculate the position to center the logo horizontally
$logoX = ($width - $logoWidth) / 2;
$logoY = $margin;

// Place the logo on the image
imagecopy($image, $logo, $logoX, $logoY, 0, 0, $logoWidth, $logoHeight);

// Load the qr.png image
$qr = imagecreatefrompng('qr.png');
$qrWidth = imagesx($qr);
$qrHeight = imagesy($qr);

// Resize the QR image to 10% of its width and keep the aspect ratio
$newQrWidth = $width * 0.10;
$newQrHeight = ($newQrWidth / $qrWidth) * $qrHeight;

// Create a new image with the new dimensions
$resizedQr = imagecreatetruecolor($newQrWidth, $newQrHeight);

// Preserve transparency
imagealphablending($resizedQr, false);
imagesavealpha($resizedQr, true);

$transparent = imagecolorallocatealpha($resizedQr, 255, 255, 255, 127);
imagefill($resizedQr, 0, 0, $transparent);

// Now resize the QR image
imagecopyresampled($resizedQr, $qr, 0, 0, 0, 0, $newQrWidth, $newQrHeight, $qrWidth, $qrHeight);
imagedestroy($qr);  // Destroy the original QR image

// Calculate position to place the resized qr.png in the bottom right corner with margins
$qrX = $width - $newQrWidth - $marginSmall;
$qrY = $height - $newQrHeight - $marginSmall;

// Place the resized QR image on the main image
imagecopy($image, $resizedQr, $qrX, $qrY, 0, 0, $newQrWidth, $newQrHeight);



$texts = [
    wordwrap($menu['titre'], 50),
    "jump",
    "jump",

];

if ($current) {
    if (count(array_filter($current['details']))) {
        $details = $current['details'];
        $texts[] = wordwrap(mb_strtoupper($details['plat_viande']), 50);
        $texts[] = wordwrap(($details['accompagnement_viande']), 50);
        $texts[] = "jump";
        $texts[] = "jump";
        $texts[] = wordwrap(mb_strtoupper($details['plat_vege']), 50);
        $texts[] = wordwrap(($details['accompagnement_vege']), 50);

    } else {
        foreach (explode("\n", strip_tags($current['description'])) as $line) {
            if (!empty($line)) {
                $texts[] = wordwrap($line, 50);
            }
            $texts[] = "jump";
        }
    }
}

// print_r($texts);exit;
// Set the font and size
$font_normal = __DIR__ . '/quicksand.ttf';  // Replace with the path to your sans-serif font file
$font_bold = __DIR__ . '/quicksand_bold.ttf';  // Replace with the path to your sans-serif font file
$fontSizeSmall = 30;
$fontSizeBig = 40;

$yOffset = $logoY + $logoHeight + $margin;
foreach ($texts as $idx => $text) {
    if ($text == 'jump') {
        $yOffset += $lineJump;
    } else {
        if ($idx) {
            $font = $font_bold;
            $fontSize = $fontSizeSmall;
        } else {
            $font = $font_normal;
            $fontSize = $fontSizeBig;
        }
        $lines = explode("\n", $text);
        foreach ($lines as $line) {
            $textBox = imagettfbbox($fontSize, 0, $font, $line);
            $textWidth = abs($textBox[4] - $textBox[0]);
            $textHeight = abs($textBox[5] - $textBox[1]);

            $xPos = ($width - $textWidth) / 2;
            $yPos = $yOffset + $textHeight;

            // Render the line
            imagettftext($image, $fontSize, 0, $xPos, $yPos, $textColor, $font, $line);

            $yOffset += ($textHeight + 5);  // Add some space between the lines
        }
    }
}

if ($zip) {
    // 1. Save the BMP file from the $image
    $bmpFileName = 'affichage.bmp';
    imagebmp($image, $bmpFileName);

    // 2. Zip the BMP file
    $zipFileName = 'affichage.zip';
    $zip = new ZipArchive();
    if ($zip->open($zipFileName, ZipArchive::CREATE) === true) {
        $zip->addFile($bmpFileName, 'affichage.bmp');
        $zip->close();
    }

    // 3. Serve the ZIP file to the user
    header('Content-Type: application/zip');
    header('Content-Length: ' . filesize($zipFileName));  // Optional but recommended
    header('Content-Disposition: attachment; filename=' . $zipFileName);
    readfile($zipFileName);

    // Clean up the files (you can remove this if you want to keep them)
    unlink($bmpFileName);
    unlink($zipFileName);
} else if ($bmp) {
    // Output the png image
    // header('Content-Disposition: attachment; filename="affichage.png"');
    header('Content-Type: image/bmp');
    imagebmp($image);
} else {
    // Output the png image
    // header('Content-Disposition: attachment; filename="affichage.png"');
    header('Content-Type: image/png');
    imagepng($image);
}
// Free the memory
imagedestroy($image);
imagedestroy($logo);
