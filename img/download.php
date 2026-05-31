<?php
$movies = [
    'Jawan_(film)' => 'jawan',
    'RRR_(film)' => 'rrr',
    'Manjummel_Boys' => 'manjummel_boys'
];

foreach ($movies as $title => $filename) {
    echo "Downloading $title...\n";
    $url = 'https://en.wikipedia.org/api/rest_v1/page/summary/' . urlencode($title);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64)");
    $json = curl_exec($ch);
    curl_close($ch);
    if ($json) {
        $data = json_decode($json, true);
        if (isset($data['thumbnail']['source'])) {
            $img_url = $data['thumbnail']['source'];
            echo "$filename: $img_url\n";

            $ch2 = curl_init();
            curl_setopt($ch2, CURLOPT_URL, $img_url);
            curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch2, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64)");
            $img_data = curl_exec($ch2);
            curl_close($ch2);

            file_put_contents("c:/xampp/htdocs/myproject/project2/img/" . $filename . ".jpg", $img_data);
        }
        else {
            echo "No thumbnail for $title\n";
        }
    }
    else {
        echo "Failed to get wiki data for $title\n";
    }
}
?>
