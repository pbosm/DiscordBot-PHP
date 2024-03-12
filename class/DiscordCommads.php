<?php

namespace Pablo\Botdiscordphp;

use Discord\Discord;
use Discord\WebSockets\Intents;
use Discord\Parts\Channel\Message;
use React\EventLoop\Factory;

class DiscordCommads {
    private $discord;
    private $loop;
    private $token = 'SEU_TOKEN';
    private $idGrupo = 'ID_DO_SEU_GRUPO';
    private $idGrupoLapou = 'ID_DO_SEU_GRUPO_TESTE';
    
    public function __construct() {
        $this->loop = Factory::create();

        $this->discord = new Discord([
            'token' => $this->token,
            'intents' => Intents::getDefaultIntents() | Intents::MESSAGE_CONTENT,
        ]);
    }

    public function run($all) {
        $this->discord->on('ready', function (Discord $discord) use ($all) {
            echo 'Bot is ready!', PHP_EOL;

            if ($all) {
                foreach ($discord->guilds as $guild) {
                    $this->loop->addPeriodicTimer(60, function () use ($guild) {
                        $this->enviarMensagemHoraAll($guild);
                    });
                }
            } else {
                $this->loop->addPeriodicTimer(60, function () use ($discord) {
                    $this->enviarMensagemGrupoEspecifico();
                });
            }

            $discord->on('message', function (Message $message, Discord $discord) {
                $this->commandsBot($message);
            });
        });

        $this->loop->run();
    }

    private function enviarMensagemHoraAll($guild) {
        $generalChannel = $guild->channels->filter(function ($channel) {
            return $channel->type == \Discord\Parts\Channel\Channel::TYPE_TEXT;
        })->first();
    
        // IDs dos usuários que você deseja mencionar
        $userIdList = [
            '239398719055855616',
            '267156811700895744',
            '187413035013505024',
            '259354437733187584'
        ];
    
        //pegar randomicamente
        $userId = $userIdList[array_rand($userIdList)];
        //marcando eles no chat
        $namesDefault = "<@{$userId}>";
    
        $horaAtual   = date('H:i');
        $minutoAtual = date('i');
    
        if ($minutoAtual == '00') {
            $generalChannel->sendMessage("Agora são {$horaAtual}, {$namesDefault}.");
        }
    }

    private function enviarMensagemGrupoEspecifico() {
        $channel = $this->discord->getChannel($this->idGrupo);

        if ($channel) {
            // IDs dos usuários que você deseja mencionar
            $userIdList = [
                'id_do_usuario',
            ];
        
            //pegar randomicamente
            $userId = $userIdList[array_rand($userIdList)];
            //marcando eles no chat
            $namesDefault = "<@{$userId}>";
        
            $horaAtual   = date('H:i');
            $minutoAtual = date('i');
        
            if ($minutoAtual == '00') {
                $channel->sendMessage("Agora são {$horaAtual}, {$namesDefault}.");
            }
        } else {
            echo "Canal não encontrado.\n";
        }
    }

    private function commandsBot($message) {
        if ($message->channel->guild_id !== $this->idGrupo) {
            return;
        }

        if ($message->author->bot) {
            return;
        }
    
        if ($message->content == '!PHP') {
            $message->channel->sendMessage('Você sabia que dá para criar um bot com PHP?');
        }
    
        if ($message->content == '!temperatura') {
            $data = new WeatherChecker;
            $cityResponse = $data->getWeather('Florianópolis');

            if ($cityResponse['temperature'] > 30) {
                $responseTemp = 'TÁ MT CALOR PITBULL';
            } else {
                $responseTemp = 'tolerável dog, tolerável';
            }
    
            $message->channel->sendMessage('Está ' . $cityResponse['temperature'] . ' °C em '. $cityResponse['city'] .', '. $responseTemp .'');
        }
    }
}

?>