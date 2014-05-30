<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

use EssentialsPE\API\Sessions;
use EssentialsPE\Loader;



class Mute extends BaseCommand{
    public function __construct(Loader $plugin){
        parent::__construct("mute", "Prevent a player from chatting", "/mute <player>", ["silence"]);
        $this->setPermission("essentials.mute.use");
        $this->plugin = $plugin;

        Sessions::getInstance()->setDefault("mute", false);
    }

    public function execute(CommandSender $sender, $alias, array $args){
        if($this->testPermission($sender)){
        }
        if(count($args) != 1){
            $sender->sendMessage(TextFormat::RED . "Usage: " . $this->getUsage());
        }else{
            $player = Server::getInstance()->getPlayer($args[0]);
            if($player instanceof Player && $player->isOnline()){
                if(Sessions::getInstance()->get($player, "mute") == false){
                    Sessions::getInstance()->set($player, "mute", true);
                    $sender->sendMessage(TextFormat::YELLOW . "$player has been muted!");
                }else{
                    Sessions::getInstance()->set($player, "mute", false);
                    $sender->sendMessage(TextFormat::YELLOW . "$player has been unmuted!");
                }
            }else{
                $sender->sendMessage(TextFormat::RED . "[Error] Player not found.");
            }
        }
    }
} 