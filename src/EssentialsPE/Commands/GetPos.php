<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use EssentialsPE\Loader;

class GetPos extends BaseCommand{
    public function __construct(Loader $plugin) {
        parent::__construct("getpos", "Get your current coords and world", "/getpos [player]", ["coords", "position", "pos", "whereami", "getlocation", "getloc"]);
        $this->setPermission("essentials.getpos.use");
        $this->plugin = $plugin;
    }
    
    public function execute(CommandSender $sender, $alias, array $args) {
        if(!$this->testPermission($sender)){
        }
        if(count($args) > 1){
            if(!$this->isPlayer($sender)){
                $sender->sendMessage(TextFormat::RED . "Usage: /getpos <player>");
            }else{
                $sender->sendMessage(TextFormat::RED . "Usage: " . $this->getUsage());
            }
        }
        switch(count($args)){
            case 0:
                if(!$this->isPlayer($sender)){
                    $sender->sendMessage(TextFormat::RED . "Usage: /getpos <player>");
                }else{
                    $pos = $sender->getPosition();
                    $sender->sendMessage(TextFormat::GREEN . "You're in world: " . TextFormat::AQUA . $pos->getLevel() . "\n" . TextFormat::GREEN . "Your Coordinates are: X: " . TextFormat::AQUA . floor($pos->x) . TextFormat::GREEN . ", Y: " . TextFormat::AQUA . floor($pos->y) . TextFormat::GREEN . ", Z: " . TextFormat::AQUA . floor($pos->z));
                }
                break;
            case 1:
                if(!$sender->hasPermission("essentials.getpos.other")){
                    $sender->sendMessage(TextFormat::RED . $this->getPermissionMessage());
                }else{
                    $player = Server::getInstance()->getPlayer($args[0]);
                    if(!$this->isPlayer($player)){
                        $sender->sendMessage(TextFormat::RED . "[Error] Player not found.");
                    }else{
                        $pos = $player->getPosition();
                        $sender->sendMessage(TextFormat::YELLOW . "$args[0]" . TextFormat::GREEN . " is in world: " . TextFormat::AQUA . $pos->getLevel() . "\n" . TextFormat::GREEN . "Coordinates: X: " . TextFormat::AQUA . floor($pos->x) . TextFormat::GREEN . ", Y: " . TextFormat::AQUA . floor($pos->y) . TextFormat::GREEN . ", Z: " . TextFormat::AQUA . floor($pos->z));
                    }
                }
                break;
        }
    }
}
