<?php
declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use Twilio\Rest\Client;

require_once('./config.php');

$dark_sky_key = getenv('DARKSKY_SECRET');
$dark_sky_uri = sprintf('https://api.darksky.net/forecast/%s/36.162663,-86.781601,%s',
    $dark_sky_key,
    time() + 43200
);

$weather_data = json_decode(file_get_contents($dark_sky_uri), true);

$sid = getenv('SID');
$token = getenv('TOKEN');
$to_number = getenv('TO_NUMBER');
$from_number = getenv('FROM_NUMBER');
$to_name = getenv('TO_NAME');
$client = new Client($sid, $token);
$chance = $weather_data['currently']['precipProbability'];
$precipType = $weather_data['currently']['precipType'];

$body = sprintf('Hey %s, it\'s going to %s tomorrow (chance: %s)! Text your friends!',
    $to_name,
    $precipType,
    $chance
);
$client->messages->create(
    $to_number,
    [
        'from' => $from_number,
        'body' => $body
    ]
);
