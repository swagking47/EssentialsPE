<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Vanish extends BaseCommand{
    public function __construct(Loader $plugin){
        parent::__construct("vanish", "Hide yourself or other player", "/vanish [player]", ["v"]);
        $this->setPermission("essentials.vanish.use");
        $this->plugin = $plugin;
    }

    public function execute(CommandSender $sender, $alias, array $args){
        if(!$this->testPermission($sender)){
            return false;
        }
        if(count($args) > 1){
            if(!$sender instanceof Player){
                $sender->sendMessage(TextFormat::RED . "Usage: /vanish <player>");
            }else{
                $sender->sendMessage(TextFormat::RED . "Usage: " . $this->getUsage());
            }
        }else{
            switch(count($args)){
                //TODO Session handler to check if the player is or not vanished(This will also help to hide the vanished player to coming players)
                case 0:
                    if(!$sender instanceof Player){
                        $sender->sendMessage(TextFormat::RED . "Usage: /vanish <player>");
                    }else{
                        /*$session = "ToDo";
                        if($session == false){
                            $sender->despawnFromAll();
                            $session = true;
                            $sender->sendMessage(TextFormat::AQUA . "You're now vanished!");
                        }else{
                            $sender->spawnToAll();
                            $session = false;
                            $sender->sendMessage(TextFormat::AQUA . "You're now visible!");
                        }*/
                    }
                    break;
                case 1:
                    $player = $this->getPlayer($args[0]);
                    if($player == false){
                        $sender->sendMessage(TextFormat::RED . "[Error] Player not found.");
                    }else{
                        /*$session = "ToDo";
                        if($session == false){
                            $player->despawnFromAll();
                            $session = true;
                            $player->sendMessage(TextFormat::AQUA . "You're now vanished!");
                            $sender->sendMessage(TextFormat::AQUA . "$args[0] is now vanished!");
                        }else{
                            $player->spawnToAll();
                            $session = false;
                            $player->sendMessage(TextFormat::AQUA . "You're now visible!");
                            $sender->sendMessage(TextFormat::AQUA . "$args[0] is now visible!");
                        }*/
                    }
                    break;
            }
        }
        return true;
    }
} 