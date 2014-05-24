<?php
namespace EssentialsPE\Commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class RealName extends Command{
    public function __construct() {
        parent::__construct("realname", "See a player realname", "/realname <player>");
        $this->setPermission("essentials.realname");
    }
    
    public function execute(CommandSender $sender, $alias, array $args){
        if(!$this->testPermission($sender)){
        }
        if(count($args) == 0 || count($args) > 1){
            $sender->sendMessage(TextFormat::RED . "Usage: " . $this->getUsage());
        }else{
            if(!$args[0] instanceof Player){
                $sender->sendMessage(TextFormat::RED . "[Error] Player not found.");
            }else{
                $sender->sendMessage(TextFormat::YELLOW . "$args[0]'s real name is: " . TextFormat::AQUA . $args[0]->getName());
            }
        }
    }
}
