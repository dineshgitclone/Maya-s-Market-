<?php

error_reporting(E_ALL);

$botToken = "8606429040:AAF4WQGPpfpL8kgd6FjzAgLXcq1XfFAfWZo";

$api = "https://api.telegram.org/bot".$botToken."/";

$offset = 0;

echo "Fast Polling Bot Started...\n";

while (true) {

    // =========================
    // CURL REQUEST
    // =========================

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL,
        $api . "getUpdates?offset=".$offset."&timeout=1"
    );

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Kill slow connection quickly
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);

    // Max total request time
    curl_setopt($ch, CURLOPT_TIMEOUT, 3);

    $response = curl_exec($ch);

    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    // =========================
    // FAILED REQUEST
    // =========================

    if ($response === false || $httpCode != 200) {

        echo "Retry...\n";

        usleep(200000);

        continue;
    }

    $data = json_decode($response, true);

    if (!isset($data["result"])) {

        usleep(200000);

        continue;
    }

    // =========================
    // PROCESS UPDATES
    // =========================

    foreach ($data["result"] as $update) {

        $offset = $update["update_id"] + 1;

        if (isset($update["message"])) {

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

                curl_setopt($send, CURLOPT_URL,
                    $api . "sendMessage"
                );

                curl_setopt($send, CURLOPT_POST, true);

                curl_setopt($send, CURLOPT_POSTFIELDS, [
                    "chat_id" => $chat_id,
                    "text" => "🚀 Before continuing, join our sponsor channels.",
                    "reply_markup" => json_encode($keyboard)
                ]);

                curl_setopt($send, CURLOPT_RETURNTRANSFER, true);

                curl_setopt($send, CURLOPT_CONNECTTIMEOUT, 2);

                curl_setopt($send, CURLOPT_TIMEOUT, 3);

                curl_exec($send);

                curl_close($send);

                echo "Reply Sent\n";
            }
        }
    }

    // Tiny delay
    usleep(200000);
}
?>
