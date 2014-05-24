<?php
namespace EssentialsPE\Commands\TP;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class TPAll extends Command{
    public function __construct() {
        parent::__construct("tpall", "Teleport every player to you", "/tpall");
        $this->setPermission("essentials.tp.tpall");
    }
    
    public function execute(CommandSender $sender, $alias, array $args) {
        if(!$this->testPermission($sender)){
            return false;
        }
        if(!$sender instanceof Player){
            $sender->sendMessage(TextFormat::RED . "Please run this command in-game.");
            return true;
        }else{
            foreach(Server::getInstance()->getOnlinePlayers() as $p){
                $p->teleport($sender->getPosition(), $sender->yaw, $sender->pitch);
                $p->sendMessage(TextFormat::YELLOW . "Teleported to $sender");
            }
            $sender->sendMessage(TextFormat::GREEN . "All the players teleported to you.");
            return true;
        }
    }
}
