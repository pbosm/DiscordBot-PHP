<?php

require __DIR__ . '/../vendor/autoload.php';

use Pablo\Botdiscordphp\DiscordCommads;

date_default_timezone_set('America/Sao_Paulo');

$bot = new DiscordCommads();
$bot->run(false);
