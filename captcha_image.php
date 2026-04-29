<?php
session_start();

// Generate soal matematika
$ops = ['+', '-'];
$op = $ops[array_rand($ops)];

if ($op === '-') {
    $a = rand(3, 10);
    $b = rand(1, $a - 1);
    $answer = $a - $b;
} else {
    $a = rand(1, 10);
    $b = rand(1, 10);
    $answer = $a + $b;
}

// Simpan jawaban di session dengan timestamp
$_SESSION['captcha_answer'] = $answer;
$_SESSION['captcha_time'] = time();

// Karakter soal
$text = "$a $op $b = ?";

// Definisi path untuk setiap karakter (simplified SVG paths, font-like)
// Setiap digit/simbol direpresentasikan sebagai SVG path agar tidak bisa di-parse sebagai teks
$charPaths = [
    '0' => 'M4 2C2.3 2 1 3.5 1 5.5v5C1 12.5 2.3 14 4 14h2c1.7 0 3-1.5 3-3.5v-5C9 3.5 7.7 2 6 2H4zM4 4.5h2c0.6 0 1 0.7 1 1.5v5c0 0.8-0.4 1.5-1 1.5H4c-0.6 0-1-0.7-1-1.5v-5C3 5.2 3.4 4.5 4 4.5z',
    '1' => 'M3 4L5.5 2V14H4V4.5H3V4z',
    '2' => 'M1 5.5C1 3.5 2.3 2 4 2h2c1.7 0 3 1.5 3 3.5v0.5H7V5.5C7 4.7 6.6 4 6 4H4C3.4 4 3 4.7 3 5.5V7c0 0.5 0.2 1 0.5 1.3L8 11.5V14H1v-2h5l-4-3.5C1.4 8 1 7.1 1 6V5.5z',
    '3' => 'M1 5C1 3.3 2.3 2 4 2h2c1.7 0 3 1.3 3 3v1.5c0 0.8-0.3 1.5-0.8 2 0.5 0.5 0.8 1.2 0.8 2V12c0 1.7-1.3 3-3 3H4c-1.7 0-3-1.3-3-3h2c0 0.6 0.4 1 1 1h2c0.6 0 1-0.4 1-1v-1.5c0-0.6-0.4-1-1-1H4V8h2c0.6 0 1-0.4 1-1V5.5c0-0.6-0.4-1-1-1H4c-0.6 0-1 0.4-1 1H1z',
    '4' => 'M7 14V10H1V8L6 2h3v6h1v2H9v4H7zM7 8V5L4 8H7z',
    '5' => 'M1 2h8v2H3v2.5C3.5 6.2 4 6 4.5 6H6c1.7 0 3 1.3 3 3v2c0 1.7-1.3 3-3 3H4c-1.7 0-3-1.3-3-3h2c0 0.6 0.4 1 1 1h2c0.6 0 1-0.4 1-1V9c0-0.6-0.4-1-1-1H4.5C2.6 8 1 6.7 1 5V2z',
    '6' => 'M6 2H4C2.3 2 1 3.3 1 5v6c0 1.7 1.3 3 3 3h2c1.7 0 3-1.3 3-3V9c0-1.7-1.3-3-3-3H3V5c0-0.6 0.4-1 1-1h2V2zM3 8h3c0.6 0 1 0.4 1 1v2c0 0.6-0.4 1-1 1H4c-0.6 0-1-0.4-1-1V8z',
    '7' => 'M1 2h8v2L5 14H3L7 4H1V2z',
    '8' => 'M4 2C2.3 2 1 3.3 1 5v1c0 0.8 0.3 1.5 0.8 2C1.3 8.5 1 9.2 1 10v1c0 1.7 1.3 3 3 3h2c1.7 0 3-1.3 3-3v-1c0-0.8-0.3-1.5-0.8-2C8.7 7.5 9 6.8 9 6V5c0-1.7-1.3-3-3-3H4zM4 4h2c0.6 0 1 0.4 1 1v1c0 0.6-0.4 1-1 1H4C3.4 7 3 6.6 3 6V5c0-0.6 0.4-1 1-1zM4 9h2c0.6 0 1 0.4 1 1v1c0 0.6-0.4 1-1 1H4c-0.6 0-1-0.4-1-1v-1c0-0.6 0.4-1 1-1z',
    '9' => 'M4 2C2.3 2 1 3.3 1 5v2c0 1.7 1.3 3 3 3h4v1c0 0.6-0.4 1-1 1H4v2h2c1.7 0 3-1.3 3-3V5c0-1.7-1.3-3-3-3H4zM4 4h2c0.6 0 1 0.4 1 1v2c0 0.6-0.4 1-1 1H4C3.4 8 3 7.6 3 7V5c0-0.6 0.4-1 1-1z',
    '+' => 'M4 2v3H1v2h3v3h2V7h3V5H6V2H4z',
    '-' => 'M1 6h8v2H1V6z',
    '×' => 'M2 3L5 6L2 9l1.5 1.5L6.5 7.5l3 3L11 9L8 6l3-3L9.5 1.5l-3 3l-3-3L2 3z',
    '=' => 'M1 4h8v2H1V4zM1 8h8v2H1V8z',
    '?' => 'M3 4C3 2.9 3.9 2 5 2s2 0.9 2 2c0 1-0.7 1.5-1.3 2C5.3 8.3 5 8.7 5 9.5H4c0-1.1 0.5-1.8 1-2.3S6 6.2 6 5.5C6 4.7 5.6 4 5 4s-1 0.7-1 1.5H3V4zM4 11h2v2H4V11z',
    ' ' => '',
];

$width = 220;
$height = 60;

header('Content-Type: image/svg+xml');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

$svg = '<svg xmlns="http://www.w3.org/2000/svg" width="'.$width.'" height="'.$height.'" viewBox="0 0 '.$width.' '.$height.'">';

// Background gradient
$svg .= '<defs><linearGradient id="bg" x1="0%" y1="0%" x2="100%" y2="100%">';
$svg .= '<stop offset="0%" style="stop-color:#141e30"/>';
$svg .= '<stop offset="100%" style="stop-color:#243b55"/>';
$svg .= '</linearGradient></defs>';
$svg .= '<rect width="'.$width.'" height="'.$height.'" fill="url(#bg)" rx="8"/>';

// Noise: garis acak
for ($i = 0; $i < 10; $i++) {
    $x1 = rand(0, $width); $y1 = rand(0, $height);
    $x2 = rand(0, $width); $y2 = rand(0, $height);
    $r = rand(40, 100); $g = rand(60, 130); $bv = rand(80, 160);
    $opacity = rand(15, 40) / 100;
    $svg .= '<line x1="'.$x1.'" y1="'.$y1.'" x2="'.$x2.'" y2="'.$y2.'" stroke="rgb('.$r.','.$g.','.$bv.')" stroke-width="'.rand(1,2).'" opacity="'.$opacity.'"/>';
}

// Noise: lingkaran
for ($i = 0; $i < 20; $i++) {
    $cx = rand(0, $width); $cy = rand(0, $height);
    $cr = rand(2, 10);
    $r = rand(50, 130); $g = rand(70, 150); $bv = rand(90, 180);
    $opacity = rand(8, 30) / 100;
    $svg .= '<circle cx="'.$cx.'" cy="'.$cy.'" r="'.$cr.'" fill="rgb('.$r.','.$g.','.$bv.')" opacity="'.$opacity.'"/>';
}

// Render karakter sebagai PATH (bukan <text>)
$chars = mb_str_split($text);
$xPos = 12;

foreach ($chars as $char) {
    if ($char === ' ') {
        $xPos += 8;
        continue;
    }
    
    $path = $charPaths[$char] ?? '';
    if (empty($path)) {
        $xPos += 12;
        continue;
    }
    
    $yOffset = rand(12, 22);
    $rotation = rand(-12, 12);
    $scale = 2.2 + (rand(0, 4) / 10); // 2.2 - 2.6
    
    // Shadow
    $svg .= '<g transform="translate('.($xPos+1).','.($yOffset+1).') rotate('.$rotation.') scale('.$scale.')">';
    $svg .= '<path d="'.$path.'" fill="rgba(80,130,180,0.3)"/>';
    $svg .= '</g>';
    
    // Karakter utama
    $svg .= '<g transform="translate('.$xPos.','.$yOffset.') rotate('.$rotation.') scale('.$scale.')">';
    $svg .= '<path d="'.$path.'" fill="white"/>';
    $svg .= '</g>';
    
    $xPos += rand(20, 28);
}

// Garis melintang di atas karakter
for ($i = 0; $i < 4; $i++) {
    $y1 = rand(10, 50); $y2 = rand(10, 50);
    $r = rand(100, 220); $g = rand(130, 240); $bv = rand(160, 255);
    $strokeW = rand(10, 20) / 10;
    $svg .= '<line x1="0" y1="'.$y1.'" x2="'.$width.'" y2="'.$y2.'" stroke="rgb('.$r.','.$g.','.$bv.')" stroke-width="'.$strokeW.'" opacity="0.25"/>';
}

// Kurva noise
for ($i = 0; $i < 2; $i++) {
    $y1 = rand(5, 55); $cy = rand(5, 55); $y2 = rand(5, 55);
    $r = rand(80, 180); $g = rand(100, 200); $bv = rand(120, 220);
    $svg .= '<path d="M0,'.$y1.' Q'.rand(50,100).','.$cy.' '.$width.','.$y2.'" fill="none" stroke="rgb('.$r.','.$g.','.$bv.')" stroke-width="1.5" opacity="0.3"/>';
}

$svg .= '</svg>';

echo $svg;
