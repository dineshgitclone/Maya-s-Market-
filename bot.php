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
        // MESSAGE
        // =========================

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
                                "text" => "ಚಾನೆಲ್‌ 1",
                                "url" => "https://telegram.me/+ur8USU3dbXgzNzQ1"
                            ]
                        ],

                        [
                            [
                                "text" => "ಚಾನೆಲ್‌ 2",
                                "url" => "https://telegram.me/+HMFa3EHiYrQxNDZl"
                            ]
                        ],

                        [
                            [
                                "text" => "ಚಾನೆಲ್‌ 3",
                                "url" => "https://telegram.me/+Ga_uWQ56MkBmNzM1"
                            ]
                        ],

                        [
                            [
                                "text" => "ಚಾನೆಲ್‌ 4",
                                "url" => "https://telegram.me/+OJ9VmFoNE9g4YmQ1"
                            ]
                        ],

                        [
                            [
                                "text" => "✅ Try Now",
                                "callback_data" => "verify"
                            ]
                        ]

                    ]
                ];

                // =========================
                // SEND PHOTO MESSAGE
                // =========================

                $send = curl_init();

                curl_setopt_array($send, [

                    CURLOPT_URL => $api . "sendPhoto",

                    CURLOPT_POST => true,

                    CURLOPT_POSTFIELDS => [

                        "chat_id" => $chat_id,

                        "photo" => "https://techzsky.com/Mm.png",

                        "caption" => "ಎಲ್ಲಾ ಚಾನೆಲ್‌ಗಳಿಗೆ ಜಾಯಿನ್ ರಿಕ್ವೆಸ್ಟ್ ಕಳುಹಿಸಿ ನಂತರ ✅Try Now ಬಟನ್ ಕ್ಲಿಕ್ ಮಾಡಿ👇",

                        "reply_markup" => json_encode($keyboard)

                    ],

                    CURLOPT_RETURNTRANSFER => false,

                    CURLOPT_CONNECTTIMEOUT => 1,

                    CURLOPT_TIMEOUT_MS => 1200,

                    CURLOPT_NOSIGNAL => 1,

                    CURLOPT_TCP_NODELAY => true,

                    CURLOPT_FORBID_REUSE => false,

                    CURLOPT_FRESH_CONNECT => false,

                    CURLOPT_SSL_VERIFYPEER => false,

                    CURLOPT_SSL_VERIFYHOST => false,

                ]);

                curl_exec($send);

                curl_close($send);

                echo "Photo Reply Sent\n";
            }
        }

        // =========================
        // CALLBACK QUERY
        // =========================

        if (isset($update["callback_query"])) {

            $callback_id = $update["callback_query"]["id"];

            $callback_data = $update["callback_query"]["data"];

            echo "Button Clicked: ".$callback_data."\n";

            // =========================
            // VERIFY BUTTON
            // =========================

            if ($callback_data == "verify") {

                $answer = curl_init();

                curl_setopt_array($answer, [

                    CURLOPT_URL => $api . "answerCallbackQuery",

                    CURLOPT_POST => true,

                    CURLOPT_POSTFIELDS => [

                        "callback_query_id" => $callback_id,

                        "text" => "⚠️ದಯವಿಟ್ಟು ಮೊದಲು ಎಲ್ಲಾ ಚಾನೆಲ್‌ಗಳಿಗೆ ಜಾಯಿನ್ ರಿಕ್ವೆಸ್ಟ್ ಕಳುಹಿಸಿ.",

                        "show_alert" => true

                    ],

                    // IMPORTANT FOR POPUP
                    CURLOPT_RETURNTRANSFER => true,

                    CURLOPT_CONNECTTIMEOUT => 1,

                    CURLOPT_TIMEOUT_MS => 3000,

                    CURLOPT_NOSIGNAL => 1,

                    CURLOPT_TCP_NODELAY => true,

                    CURLOPT_FORBID_REUSE => false,

                    CURLOPT_FRESH_CONNECT => false,

                    CURLOPT_SSL_VERIFYPEER => false,

                    CURLOPT_SSL_VERIFYHOST => false,

                ]);

                $result = curl_exec($answer);

                echo $result."\n";

                curl_close($answer);

                echo "Verify Popup Sent\n";
            }
        }
    }

    // =========================
    // TINY CPU RELAX
    // =========================

    usleep(10000);
}

?>
