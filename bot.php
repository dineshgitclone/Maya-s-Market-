<?php

// =========================
// TELEGRAM BOT CONFIG
// =========================

$botToken = "8606429040:AAF4WQGPpfpL8kgd6FjzAgLXcq1XfFAfWZo";
$api = "https://api.telegram.org/bot".$botToken."/";

// =========================
// SHOW MESSAGE ON WEBSITE
// =========================

if (isset($_SERVER['REQUEST_METHOD'])) {

    echo "<h1>Bot Online 🚀</h1>";
    echo "<p>Telegram bot is running successfully.</p>";

    exit;
}

// =========================
// START POLLING
// =========================

$offset = 0;

echo "Bot Started...\n";

while (true) {

    // GET UPDATES
    $response = @file_get_contents(
        $api . "getUpdates?timeout=10&offset=".$offset
    );

    // CONNECTION FAILED
    if ($response === false) {

        echo "Connection Failed...\n";
        sleep(2);

        continue;
    }

    $data = json_decode($response, true);

    // CHECK NEW UPDATES
    if (isset($data["result"])) {

        foreach ($data["result"] as $update) {

            $offset = $update["update_id"] + 1;

            // CHECK MESSAGE
            if (isset($update["message"])) {

                $chat_id = $update["message"]["chat"]["id"];
                $text = $update["message"]["text"] ?? "";

                // START COMMAND
                if ($text == "/start") {

                    $message = "🚀 Before continuing, you need to join our sponsor channels.";

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
                    @file_get_contents(
                        $api . "sendMessage?" . http_build_query([
                            "chat_id" => $chat_id,
                            "text" => $message,
                            "reply_markup" => json_encode($keyboard)
                        ])
                    );

                    echo "Message Sent To ".$chat_id."\n";
                }
            }
        }
    }

    sleep(1);
}
?>
