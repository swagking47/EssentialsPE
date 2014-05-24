<?php
/*
 * NOTE: THIS WILL NOT WORK UNTIL POCKETMINE CAN HANDLE ENTITY HEALTH CHANGE AND SPEED DETECT
 */

namespace EssentialsPE\Commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

use EssentialsPE\API\Sessions;

class AFK extends Command{
    public $sessions;
    
    public function __construct() {
        parent::__construct("afk", "Toggle AFK mode", "/afk", ["away"]);
        $this->setPermission("essentials.afk");
        
        $this->sessions = new Sessions();
        $this->sessions->setDefault("afk", false);
    }
    
    public function execute(CommandSender $sender, $alias, array $args) {
        if(!$this->testPermission($sender)){
        }
        if(!$sender instanceof Player){
            $sender->sendMessage(TextFormat::RED . "Please run this command in-game.");
            return true;
        }else{
            if($this->sessions->get($sender, "afk") == false){
                $this->sessions->set($sender, "afk", true);
                $sender->sendMessage(TextFormat::GREEN . "AFK Mode activated!");
                //Server::getInstance()->broadcastMessage(TextFormat::GRAY . "[INFO] $sender is now AFK");
            }else{
                $this->sessions->set($sender, "afk", false);
                $sender->sendMessage(TextFormat::GREEN . "AFK Mode deactivated!");
                //Server::getInstance()->broadcastMessage(TextFormat::GRAY . "[INFO] $sender is no longer AFK");
            }
            return true;
        }
    }
}
