<?php
namespace EssentialsPE\Commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class Broadcast extends Command{
    public function __construct() {
        parent::__construct("broadcast", "Send a message to all the players", "/broadcast <message> [permission]", ["bcast"]);
        $this->setPermission("essentials.broadcast");
    }
    
    public function execute(CommandSender $sender, $alias, array $args) {
        if(!$this->testPermission($sender)){
            return false;
        }
        if(count($args) == 0){
            $sender->sendMessage(TextFormat::RED . "Usage: " . $this->getUsage());
        }else{
            $message = TextFormat::LIGHT_PURPLE . "[Broadcast] " . TextFormat::RESET . implode(" ", str_replace("&", "§", $args));
            if(stripos($message, "p:") != false){
                $pos = stripos($message, "p:");
                $permission = substr($message, $pos);
                $message = substr_replace($message, "", $pos);
                Server::getInstance()->broadcast($message, str_replace("p:", "", $permission));
            }else{
                Server::getInstance()->broadcastMessage($message);
            }
        }
    }
}
