<?php
/*
 * NOTE: THIS WILL NOT WORK UNTIL POCKETMINE CAN HANDLE PLAYER <-> PLAYER INTERACTION
 */

namespace EssentialsPE\Commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

use EssentialsPE\API\Sessions;

class PvP extends Command{
    public $sessions;
    
    public function __construct() {
        parent::__construct("pvp", "Toggle PvP", "/pvp <on|off>");
        $this->setPermission("essentials.pvp");
        
        $this->sessions = new Sessions;
        $this->sessions->setDefault("pvp", false);
    }
    
    public function execute(CommandSender $sender, $alias, array $args) {
        if(!$this->testPermission($sender)){
        }
        if(!($sender instanceof Player)){
            $sender->sendMessage(TextFormat::RED . "Please run this command in-game.");
        }else{
            switch(count($args)){
                case 0:
                    $sender->sendMessage(TextFormat::RED . "Usage: " . $this->getUsage());
                    break;
                case 1:
                    switch($args[0]){
                        case "on":
                            if($this->sessions->get($sender, "pvp") == false){
                                $this->sessions->set($sender, "pvp", true);
                                $sender->sendMessage(TextFormat::GREEN . "PvP enabled!");
                            }
                            break;
                        case "off":
                            if($this->sessions->get($sender, "pvp") == true){
                                $this->sessions->set($sender, "pvp", false);
                                $sender->sendMessage(TextFormat::GREEN . "PvP disabled!");
                            }
                            break;
                    }
            }
        }
    }
}
