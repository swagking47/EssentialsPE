<?php
namespace EssentialsPE\Commands\TP\Requests;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

use EssentialsPE\API\Sessions;

class TPAAll extends Command{
    public $sessions;

    public function __construct() {
        parent::__construct("tpaall", "Request a tp to you to all the players", "/tpaall");
        $this->setPermission("essentials.tp.requests.tpaall");
        
        $this->sessions = new Sessions;
    }
    
    public function execute(CommandSender $sender, $commandLabel, string $args) {
        if(!$this->testPermission($sender)){
            return false;
        }
        if(!$sender instanceof Player){
            $sender->sendMessage(TextFormat::RED . "Please run this command in-game.");
            return true;
        }else{
            foreach(Server::getInstance()->getOnlinePlayers() as $p){
                $this->sessions->set($p, "tpa", null);
                $this->sessions->set($p, "tpahere", $sender);
                
                $player->sendMessage(TextFormat::YELLOW . "$sender wanna teleport you to him:\n Use /tpaccept to accept or \n /tpdeny to ignore.");
            }
            $this->sessions->set($sender, "tprequest", "@all");
            $sender->sendMessage(TextFormat::YELLOW . "Request send to everyone.");
            return true;
        }
    }
}
