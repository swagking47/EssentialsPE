<?php
namespace EssentialsPE\Commands\TP\Requests;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

use EssentialsPE\API\Sessions;

class TPA extends Command{
    public $sessions;
    
    public function __construct() {
        parent::__construct("tpa", "Make a teleport request", "/tpa <player>", ["call", "tpask"]);
        $this->setPermission("essentials.tp.requests.tpa");
        
        $this->sessions = new Sessions();
        $this->sessions->setDefault("tpa", null);
        $this->sessions->setDefault("tprequest", null);
    }
    
    public function execute(CommandSender $sender, $alias, array $args) {
        if(!$this->testPermission($sender)){
            return false;
        }
        if(!$sender instanceof Player){
            $sender->sendMessage(TextFormat::RED . "Please run this command in-game.");
            return true;
        }else{
            switch(count($args)){
                case 0:
                    $sender->sendMessage(TextFormat::RED . "Usage: " . $this->getUsage());
                    break;
                case 1:
                    if(!$args[0] instanceof Player){
                        $sender->sendMessage(TextFormat::RED . "[Error] Player not found.");
                    }else{
                        $player = Server::getInstance()->getPlayer($args[0]);
                        
                        $this->sessions->set($sender, "tprequest", $player);
                        $this->sessions->set($player, "tpa", $sender);
                        $this->sessions->set($player, "tpahere", null);
                        
                        $player->sendMessage(TextFormat::YELLOW . "$sender wanna teleport to you:\n Use /tpaccept to accept or \n /tpdeny to ignore.");
                        $sender->sendMessage(TextFormat::YELLOW . "Request send to $player");
                    }
            }
            return true;
        }
    }
}
