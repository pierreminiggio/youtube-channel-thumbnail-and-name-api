<?php

$channelUrl = $_GET['channel_url'] ?? null;

if (! $channelUrl) {
    http_response_code(400);
    exit;
}

$curl = curl_init('https://api.codexradar.com/youtubeprofilerpicturedownloader/');
curl_setopt_array($curl, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HEADER => [
        'authority' => 'api.codexradar.com',
        'sec-ch-ua' => '"Chromium";v="92", " Not A;Brand";v="99", "Google Chrome";v="92"',
        'accept' => '*/*',
        'sec-ch-ua-mobile' => '?0',
        'user-agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/92.0.4515.159 Safari/537.36',
        'content-type' => 'application/x-www-form-urlencoded; charset=UTF-8',
        'origin' => 'https://www.codexradar.com',
        'sec-fetch-site' => 'same-site',
        'sec-fetch-mode' => 'cors',
        'sec-fetch-dest' => 'empty',
        'referer' => 'https://www.codexradar.com/',
        'accept-language' => 'fr-FR,fr;q=0.9'
    ],
    CURLOPT_POSTFIELDS => 'url=' . $channelUrl
]);

$response = curl_exec($curl);

$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

if ($httpcode !== 200) {
    http_response_code(500);
    exit;
}

if (! $response) {
    http_response_code(500);
    exit;
}

$explodedResponse = explode(PHP_EOL, $response);

foreach ($explodedResponse as $explodedResponseLineIndex => $explodedResponseLine) {
    if ($explodedResponseLine === '') {
        break;
    }
}

$responseLines = array_splice($explodedResponse, $explodedResponseLineIndex);

$responseText = implode($responseLines);

if (! $responseText) {
    http_response_code(500);
    exit;
}

$jsonResponse = json_decode($responseText, true);

if ($jsonResponse === null) {
    http_response_code(404);
    exit;
}

http_response_code(200);
echo $responseText;