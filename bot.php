<?php

$botToken = "8606429040:AAF4WQGPpfpL8kgd6FjzAgLXcq1XfFAfWZo";
$api = "https://api.telegram.org/bot$botToken/";

$last_update_id = 0;

while (true) {

    $updates = json_decode(file_get_contents(
        $api . "getUpdates?offset=" . ($last_update_id + 1) . "&timeout=30"
    ), true);

    if (isset($updates["result"])) {

        foreach ($updates["result"] as $update) {

            $last_update_id = $update["update_id"];

            $chat_id = $update["message"]["chat"]["id"];
            $text = $update["message"]["text"];

            if ($text == "/start") {

                $message = "🚀 Before continuing, join our sponsor channels.";

                $keyboard = [
                    "inline_keyboard" => [
                        [
                            ["text" => "Join Channel 1", "url" => "https://t.me/channel1"],
                            ["text" => "Join Channel 2", "url" => "https://t.me/channel2"]
                        ],
                        [
                            ["text" => "Join Channel 3", "url" => "https://t.me/channel3"],
                            ["text" => "Join Channel 4", "url" => "https://t.me/channel4"]
                        ]
                    ]
                ];

                file_get_contents($api . "sendMessage?" . http_build_query([
                    "chat_id" => $chat_id,
                    "text" => $message,
                    "reply_markup" => json_encode($keyboard)
                ]));
            }
        }
    }

    sleep(1);
}
?>
