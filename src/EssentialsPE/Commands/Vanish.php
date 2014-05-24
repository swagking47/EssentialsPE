<?php
namespace EssentialsPE\Commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

use EssentialsPE\API\Sessions;

class Vanish extends Command{
    public $sessions;
    
    public function __construct(){
        parent::__construct("vanish", "Hide yourself!", "/vanish", ["v"]);
        $this->setPermission("essentials.vanish");
        
        $this->sessions = new Sessions();
        $this->sessions->setDefault("vanish", false);
    }
    
    public function execute(CommandSender $sender, $alias, array $args) {
        if(!$this->testPermission($sender)){
        }
        if(!($sender instanceof Player)){
            $sender->sendMessage(TextFormat::RED . "Please run this commans in-game.");
            return true;
        }else{
            if($this->sessions->get($sender, "vanish") == true){
                foreach(Server::getInstance()->getOnlinePlayers() as $p){
                    if($p != $sender){
                        $p->showPlayer($sender);
                    }
                }
                $this->sessions->set($sender, "vanish", false);
            }else{
                foreach(Server::getInstance()->getOnlinePlayers() as $p){
                    if($p != $sender){
                        $p->hidePlayer($sender);
                    }
                }
                $this->sessions->set($sender, "vanish", true);
            }
        }
    }
}
