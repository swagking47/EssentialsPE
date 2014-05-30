<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use EssentialsPE\Loader;

class Broadcast extends BaseCommand{
    public function __construct(Loader $plugin) {
        parent::__construct("broadcast", "Send a message to all the players", "/broadcast <message> [p:permission]", ["bcast"]);
        $this->setPermission("essentials.broadcast");
        $this->plugin = $plugin;
    }
    
    public function execute(CommandSender $sender, $alias, array $args) {
        if(!$this->testPermission($sender)){
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
