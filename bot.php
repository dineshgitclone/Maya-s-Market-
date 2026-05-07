<?php

$botToken = "YOUR_BOT_TOKEN";
$api = "https://api.telegram.org/bot".$botToken."/";

$offset = 0;

echo "Polling Started...\n";

while (true) {

    $response = @file_get_contents(
        $api . "getUpdates?timeout=10&offset=".$offset
    );

    if ($response === false) {
        sleep(2);
        continue;
    }

    $data = json_decode($response, true);

    if (isset($data["result"])) {

        foreach ($data["result"] as $update) {

            $offset = $update["update_id"] + 1;

            if (isset($update["message"])) {

                $chat_id = $update["message"]["chat"]["id"];
                $text = $update["message"]["text"] ?? "";

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

                    @file_get_contents(
                        $api . "sendMessage?" . http_build_query([
                            "chat_id" => $chat_id,
                            "text" => "🚀 Before continuing, join our sponsor channels.",
                            "reply_markup" => json_encode($keyboard)
                        ])
                    );

                    echo "Message Sent\n";
                }
            }
        }
    }

    sleep(1);
}
?>
