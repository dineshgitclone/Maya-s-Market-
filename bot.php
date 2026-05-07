<?php

$botToken = "8606429040:AAF4WQGPpfpL8kgd6FjzAgLXcq1XfFAfWZo";
$website = "https://api.telegram.org/bot".$botToken;

$update = file_get_contents("php://input");
$update = json_decode($update, TRUE);

$chat_id = $update["message"]["chat"]["id"];
$text = $update["message"]["text"];

// Welcome message
if ($text == "/start") {

    $message = "🚀 Before continuing, you must join our sponsor channels:\n\nAfter joining, you can proceed.";

    $keyboard = [
        "inline_keyboard" => [
            [
                ["text" => "Join Channel 1", "url" => "https://t.me/channel1"],
                ["text" => "Join Channel 2", "url" => "https://t.me/channel2"]
            ],
            [
                ["text" => "Join Channel 3", "url" => "https://t.me/channel3"],
                ["text" => "Join Channel 4", "url" => "https://t.me/channel4"]
            ],
            [
                ["text" => "✅ I Joined", "callback_data" => "check_join"]
            ]
        ]
    ];

    sendMessage($chat_id, $message, $keyboard);
}

// Send Message Function
function sendMessage($chat_id, $text, $keyboard = null) {
    global $website;

    $url = $website."/sendMessage?chat_id=".$chat_id."&text=".urlencode($text)."&parse_mode=HTML";

    if ($keyboard != null) {
        $url .= "&reply_markup=".json_encode($keyboard);
    }

    file_get_contents($url);
}

?>
