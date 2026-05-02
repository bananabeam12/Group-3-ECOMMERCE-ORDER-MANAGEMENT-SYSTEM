<?php
session_start();

$chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
$captchaText = '';
for ($i = 0; $i < 5; $i++) {
    $captchaText .= $chars[random_int(0, strlen($chars) - 1)];
}

$_SESSION['captcha'] = $captchaText;

$image = imagecreatetruecolor(200, 60);

$background = imagecolorallocate($image, 245, 245, 245);
$textColor   = imagecolorallocate($image, 50, 50, 50);
$noiseColor  = imagecolorallocate($image, 180, 180, 180);

imagefilledrectangle($image, 0, 0, 200, 60, $background);

for ($i = 0; $i < 5; $i++) {
    imageline($image, random_int(0, 200), random_int(0, 60),
                      random_int(0, 200), random_int(0, 60), $noiseColor);
}

for ($i = 0; $i < 80; $i++) {
    imagesetpixel($image, random_int(0, 200), random_int(0, 60), $noiseColor);
}

$fontSize = 5;
$x = 20;
foreach (str_split($captchaText) as $char) {
    $y = random_int(15, 25);
    imagestring($image, $fontSize, $x, $y, $char, $textColor);
    $x += 35;
}

header('Content-Type: image/png');
header('Cache-Control: no-cache, no-store');
imagepng($image);
?>