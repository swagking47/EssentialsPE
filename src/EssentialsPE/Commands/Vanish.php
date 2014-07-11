<?php
namespace EssentialsPE\Commands;

use EssentialsPE\API;
use EssentialsPE\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Vanish extends BaseCommand{
    public $vanished = [];

    public function __construct(Loader $plugin){
        parent::__construct($plugin, "vanish", "Hide from other players!", "/vanish", ["v"]);
        $this->setPermission("essentials.command.vanish.use");
    }

    public function execute(CommandSender $sender, $alias, array $args){
        if(!$this->testPermission($sender)){
            return false;
        }
        if(count($args) > 1){
            $sender->sendMessage(TextFormat::RED . "Usage: " . $this->getUsage());
        }else{
            switch(count($args)){
                case 0:
                    if(!$sender instanceof Player){
                        $sender->sendMessage(TextFormat::RED . "Usage: " . $this->getUsage());
                    }else{
                        $this->api->switchVanish($sender);
                        if(!$this->api->isVanished($sender)){
                            $sender->sendMessage(TextFormat::GRAY . "You're now visible");
                        }else{
                            $sender->sendMessage(TextFormat::GRAY . "You're now vanished!");
                        }
                    }
                    break;
                case 1:
                    $player = $this->getPlayer($args[0]);
                    if($player == false){
                        $sender->sendMessage(TextFormat::RED . "[Error] Player not found.");
                    }else{
                        $this->api->switchVanish($player);
                        if(!$this->api->isVanished($player)){
                            $player->sendMessage(TextFormat::GRAY . "You're now visible");
                            $sender->sendMessage(TextFormat::GRAY . "$args[0] is now visible");
                        }else{
                            $player->sendMessage(TextFormat::GRAY . "You're now vanished!");
                            $sender->sendMessage(TextFormat::GRAY . "$args[0] is now vanished!");
                        }
                    }
                    break;
            }
        }
        return true;
    }
} 