<?php
namespace EssentialsPE\Commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class GetPos extends Command{
    public function __construct() {
        parent::__construct("getpos", "Get your current coords and world", "/getpos [player]", ["coords", "position", "pos", "whereami", "getlocation", "getloc"]);
        $this->setPermission("essentials.getpos");
    }
    
    public function execute(CommandSender $sender, $alias, array $args) {
        if(!$this->testPermission($sender)){
            return false;
        }
        switch(count($args)){
            case 0:
                if(!$sender instanceof Player){
                    $sender->sendMessage(TextFormat::RED . "Usage: " . $this->getUsage());
                }else{
                    if(!$sender->hasPermission("essentials.getpos.use")){
                        $sender->sendMessage(TextFormat::RED . "You don't have permissions to use this command.");
                    }else{
                        $pos = $sender->getPosition();
                        $sender->sendMessage(TextFormat::GREEN . "You're in world: " . TextFormat::AQUA . $pos->level . TextFormat::GREEN . "\nYour Coordinates are:\nX: " . TextFormat::AQUA . $pos->x . TextFormat::GREEN . ", Y: " . TextFormat::AQUA . $pos->y . TextFormat::GREEN . ", Z: " . TextFormat::AQUA . $pos->z);
                    }
                }
                break;
            case 1:
                if(!$sender->hasPermission("essentials.getpos.other")){
                    $sender->sendMessage(TextFormat::RED . "You don't have permissions to use this command.");
                }else{
                    if(!$args[0] instanceof Player){
                        $sender->sendMessage(TextFormat::RED . "[Error] Player not found.");
                    }else{
                        $pos = $args[0]->getPosition();
                        $sender->sendMessage(TextFormat::GREEN . "$args[0] is on world: " . TextFormat::AQUA . $pos->level . TextFormat::GREEN . "\nCoordinates:\nX: " . TextFormat::AQUA . $pos->x . TextFormat::GREEN . ", Y: " . TextFormat::AQUA . $pos->y . TextFormat::GREEN . ", Z: " . TextFormat::AQUA . $pos->z);
                    }
                }
                break;
        }
    }
}
