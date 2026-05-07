<?php

error_reporting(E_ALL);

// =========================
// TELEGRAM BOT CONFIG
// =========================

$botToken = "8606429040:AAF4WQGPpfpL8kgd6FjzAgLXcq1XfFAfWZo";
$api = "https://api.telegram.org/bot".$botToken."/";

// =========================
// POLLING SETTINGS
// =========================

$offset = 0;

echo "Bot Started...\n";

// =========================
// MAIN LOOP
// =========================

while (true) {

    // GET NEW UPDATES FAST
    $response = @file_get_contents(
        $api . "getUpdates?offset=".$offset."&timeout=1"
    );

    // IF FAILED
    if ($response === false) {

        echo "Connection Failed\n";

        usleep(500000);
        continue;
    }

    // DECODE RESPONSE
    $data = json_decode($response, true);

    // CHECK NEW MESSAGES
    if (isset($data["result"])) {

        foreach ($data["result"] as $update) {

            // SAVE UPDATE ID
            $offset = $update["update_id"] + 1;

            // CHECK MESSAGE
            if (isset($update["message"])) {

                $chat_id = $update["message"]["chat"]["id"];
                $text = $update["message"]["text"] ?? "";

                echo "Message: ".$text."\n";

                // START COMMAND
                if ($text == "/start") {

                    $message = "🚀 Before continuing, you need to join our sponsor channels.";

                    // BUTTONS
                    $keyboard = [
                        "inline_keyboard" => [

                            [
                                [
                                    "text" => "Join Channel 1",
                                    "url" => "https://t.me/channel1"
                                ]
                            ],

                            [
                                [
                                    "text" => "Join Channel 2",
                                    "url" => "https://t.me/channel2"
                                ]
                            ],

                            [
                                [
                                    "text" => "Join Channel 3",
                                    "url" => "https://t.me/channel3"
                                ]
                            ],

                            [
                                [
                                    "text" => "Join Channel 4",
                                    "url" => "https://t.me/channel4"
                                ]
                            ]

                        ]
                    ];

                    // SEND MESSAGE
                    $send = @file_get_contents(
                        $api . "sendMessage?" . http_build_query([
                            "chat_id" => $chat_id,
                            "text" => $message,
                            "reply_markup" => json_encode($keyboard)
                        ])
                    );

                    echo "Reply Sent\n";
                }
            }
        }
    }

    // SMALL DELAY
    usleep(300000);
}
?>
