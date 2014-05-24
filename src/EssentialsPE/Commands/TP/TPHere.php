<?php
namespace EssentialsPE\Commands\TP;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class TPHere extends Command{
    public function __construct(){
        parent::__construct("tphere", "Teleport the desired player to you", "/tphere <player>");
        $this->setPermission("essentials.tp.tphere");
    }
    
    public function execute(CommandSender $sender, $alias, array $args) {
        if(!$this->testPermission($sender)){
            return false;
        }
        if(!$sender instanceof Player){
            $sender->sendMessage(TextFormat::RED . "Please run this command in-game.");
            return true;
        }else{
            switch(count($args)){
                case 0:
                    $sender->sendMessage(TextFormat::RED . "Usage: " . $this->getUsage());
                    break;
                case 1:
                    if(!$args[0] instanceof Player){
                        $sender->sendMessage(TextFormat::RED . "[Error] Player not found");
                    }else{
                       $args[0]->teleport($sender->getPosition(), $sender->yaw, $sender->pitch);
                       $args[0]->sendMessage(TextFormat::YELLOW . "Teleported to $sender");
                       $sender->sendMessage(TextFormat::GREEN . "Teleported $args[0] to you");
                       
                    }
                    break;
            }
            return true;
        }
    }
}
