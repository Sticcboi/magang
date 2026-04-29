<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

function convert_to_embed_url($url) {
    if (empty($url)) return '';
    
    // If it's already an iframe or embed url
    if (strpos(strtolower($url), '<iframe') !== false && preg_match('/src=["\'](.*?)["\']/', $url, $matches)) {
        return $matches[1];
    }
    if (strpos($url, 'embed') !== false || strpos($url, 'output=embed') !== false) {
        return $url;
    }
    
    // Resolve short links
    if (strpos($url, 'goo.gl') !== false || strpos($url, 'maps.app.goo.gl') !== false) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
        $response = curl_exec($ch);
        $final_url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
        curl_close($ch);
        
        if ($final_url && $final_url !== $url) {
            $url = $final_url;
        }
    }
    
    // Extract coordinates from full URL: /@LAT,LNG,
    if (preg_match('/@(-?\d+\.\d+),(-?\d+\.\d+)/', $url, $matches)) {
        $lat = $matches[1];
        $lng = $matches[2];
        return "https://maps.google.com/maps?q=$lat,$lng&output=embed";
    }
    
    // If no coordinates, maybe extract place name?
    if (preg_match('/place\/([^\/]+)/', $url, $matches)) {
        $place = str_replace('+', ' ', $matches[1]);
        return "https://maps.google.com/maps?q=" . urlencode($place) . "&output=embed";
    }
    
    return $url;
}

$tests = [
    [
        'input' => '<iframe src="https://www.google.com/maps/embed?pb=123" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>',
        'expected' => 'https://www.google.com/maps/embed?pb=123',
        'name' => 'Extract from iframe'
    ],
    [
        'input' => 'https://maps.google.com/maps?q=-7.033,110.339&output=embed',
        'expected' => 'https://maps.google.com/maps?q=-7.033,110.339&output=embed',
        'name' => 'Leave embed url untouched'
    ],
    [
        'input' => 'https://www.google.com/maps/place/Pasar+Mijen/@-7.0544158,110.3168172,17z/',
        'expected' => 'https://maps.google.com/maps?q=-7.0544158,110.3168172&output=embed',
        'name' => 'Extract coordinates from full URL'
    ],
    [
        'input' => 'https://www.google.com/maps/place/Kedai+Makmur/',
        'expected' => 'https://maps.google.com/maps?q=Kedai+Makmur&output=embed',
        'name' => 'Extract place name from full URL without coordinates'
    ],
    // The short link test might be flaky depending on network, but let's test it
    [
        'input' => 'https://maps.app.goo.gl/1wZf3M4bH3pYX2fQ7', // Dummy or real shortlink
        'expected_contains' => 'output=embed',
        'name' => 'Resolve shortlink and convert'
    ]
];

$passed = 0;
$failed = 0;
$results = [];

foreach ($tests as $t) {
    $result = convert_to_embed_url($t['input']);
    
    if (isset($t['expected'])) {
        $success = ($result === $t['expected']);
    } else if (isset($t['expected_contains'])) {
        $success = (strpos($result, $t['expected_contains']) !== false);
    }
    
    if ($success) {
        $passed++;
        $results[] = "✅ PASSED: {$t['name']} | Result: $result";
    } else {
        $failed++;
        $expected = $t['expected'] ?? 'Contains: ' . $t['expected_contains'];
        $results[] = "❌ FAILED: {$t['name']} | Expected: $expected | Got: $result";
    }
}

echo "<h2>TDD Results: GMaps Resolver</h2>";
echo "<b>Passed: $passed | Failed: $failed</b><br><br>";
foreach ($results as $r) {
    echo $r . "<br>";
}
