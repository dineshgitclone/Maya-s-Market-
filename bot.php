<?php

error_reporting(E_ALL);

// =========================
// BOT CONFIG
// =========================

$botToken = "8606429040:AAF4WQGPpfpL8kgd6FjzAgLXcq1XfFAfWZo";
$api = "https://api.telegram.org/bot".$botToken."/";

$offset = 0;

echo "Ultra Fast Polling Bot Started...\n";

// =========================
// MAIN LOOP
// =========================

while (true) {

    // =========================
    // GET UPDATES
    // =========================

    $ch = curl_init();

    curl_setopt_array($ch, [

        CURLOPT_URL => $api . "getUpdates?offset=".$offset."&timeout=0",

        CURLOPT_RETURNTRANSFER => true,

        CURLOPT_CONNECTTIMEOUT => 1,

        CURLOPT_TIMEOUT_MS => 900,

        CURLOPT_NOSIGNAL => 1,

        CURLOPT_TCP_NODELAY => true,

        CURLOPT_FORBID_REUSE => false,

        CURLOPT_FRESH_CONNECT => false,

        CURLOPT_SSL_VERIFYPEER => false,

        CURLOPT_SSL_VERIFYHOST => false,

    ]);

    $response = curl_exec($ch);

    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    // =========================
    // CONNECTION FAILED
    // =========================

    if ($response === false || $httpCode != 200) {

        echo "Connection Failed\n";

        usleep(10000);

        continue;
    }

    // =========================
    // DECODE JSON
    // =========================

    $data = json_decode($response, true);

    if (empty($data["result"])) {

        usleep(10000);

        continue;
    }

    // =========================
    // PROCESS UPDATES
    // =========================

    foreach ($data["result"] as $update) {

        // IMPORTANT
        $offset = $update["update_id"] + 1;

        // =========================
        // MESSAGE CHECK
        // =========================

        if (!isset($update["message"])) {
            continue;
        }

        $chat_id = $update["message"]["chat"]["id"];

        $text = $update["message"]["text"] ?? "";

        echo "Message: ".$text."\n";

        // =========================
        // START COMMAND
        // =========================

        if ($text == "/start") {

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

            // =========================
            // SEND MESSAGE
            // =========================

            $send = curl_init();

            curl_setopt_array($send, [

                CURLOPT_URL => $api . "sendMessage",

                CURLOPT_POST => true,

                CURLOPT_POSTFIELDS => [

                    "chat_id" => $chat_id,

                    "text" => "🚀 Before continuing, join our sponsor channels.",

                    "reply_markup" => json_encode($keyboard)

                ],

                // Faster response
                CURLOPT_RETURNTRANSFER => false,

                CURLOPT_CONNECTTIMEOUT => 1,

                CURLOPT_TIMEOUT_MS => 800,

                CURLOPT_NOSIGNAL => 1,

                CURLOPT_TCP_NODELAY => true,

                CURLOPT_FORBID_REUSE => false,

                CURLOPT_FRESH_CONNECT => false,

                CURLOPT_SSL_VERIFYPEER => false,

                CURLOPT_SSL_VERIFYHOST => false,

            ]);

            curl_exec($send);

            curl_close($send);

            echo "Reply Sent\n";
        }
    }

    // Tiny CPU relax
    usleep(10000);
}
?>
