<?php
declare(strict_types=1);

/* Captcha image for guest comments / uploads. The code is created by the page that shows the form. */

require __DIR__ . '/include/Security.php';
Security::startSession();

$code = is_string($_SESSION['_captcha'] ?? null) ? $_SESSION['_captcha'] : '';
header('Cache-Control: no-store');
header('X-Content-Type-Options: nosniff');

if (!extension_loaded('gd') || $code === '') {
    http_response_code(404);
    exit;
}

$w = 180;
$h = 50;
$img = imagecreatetruecolor($w, $h);
imagefilledrectangle($img, 0, 0, $w, $h, imagecolorallocate($img, 245, 245, 245));
for ($i = 0; $i < 6; $i++) {
    $c = imagecolorallocate($img, random_int(150, 210), random_int(150, 210), random_int(150, 210));
    imageline($img, random_int(0, $w), random_int(0, $h), random_int(0, $w), random_int(0, $h), $c);
}
for ($i = 0; $i < 400; $i++) {
    imagesetpixel($img, random_int(0, $w - 1), random_int(0, $h - 1), imagecolorallocate($img, random_int(100, 200), random_int(100, 200), random_int(100, 200)));
}
$x = 18;
foreach (str_split($code) as $char) {
    $color = imagecolorallocate($img, random_int(10, 90), random_int(10, 90), random_int(10, 90));
    imagestring($img, 5, $x, random_int(8, 24), $char, $color);
    $x += random_int(22, 26);
}
header('Content-Type: image/png');
imagepng($img);
