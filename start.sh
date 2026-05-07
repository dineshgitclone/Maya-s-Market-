#!/bin/sh

php -S 0.0.0.0:8080 > /dev/null 2>&1 &

php bot.php
