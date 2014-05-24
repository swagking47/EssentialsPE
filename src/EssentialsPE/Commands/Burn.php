<?php
namespace EssentialsPE\Commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Burn extends Command{
    public function __construct() {
        parent::__construct("burn", "Set any player on fire!", "/burn <player> <seconds>");
        $this->setPermission("essentials.burn");
    }
    
    public function execute(CommandSender $sender, $alias, array $args) {
        if(!$this->testPermission($sender)){
            return false;
        }
        switch(count($args)){
            case 0:
            case 1:
                $sender->sendMessage(TextFormat::RED . "Usage: " . $this->getUsage());
                break;
            case 2:
                if(!$args[0] instanceof Player){
                    $sender->sendMessage(TextFormat::RED . "[Error] Player not found.");
                }else{
                    if(!is_numeric($args[1])){
                        $sender->sendMessage(TextFormat::RED . "[Error] Invalid numbers.");
                    }else{
                        $args[0]->setOnFire($args[1]);
                        $sender->sendMessage(TextFormat::YELLOW . "Player is now on fire!");
                    }
                }
        }
    }
}
