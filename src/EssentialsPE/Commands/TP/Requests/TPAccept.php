<?php
namespace EssentialsPE\Commands\TP\Requests;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
//use pocketmine\Server;
use pocketmine\utils\TextFormat;

use EssentialsPE\API\Sessions;

class TPAccept extends Command{
    public $sessions;
    
    public function __construct() {
        parent::__construct("tpaccept", "Accept the lastest teleport request", "/tpaccept", ["tpyes"]);
        $this->setPermission("essentials.tp.requests.tpaccept");
        
        $this->sessions = new Sessions();
    }
    
    public function execute(CommandSender $sender, $alias, array $args) {
        if(!$this->testPermission($sender)){
            return false;
        }
        if(!$sender instanceof Player){
            $sender->sendMessage(TextFormat::RED . "Please run this command in-game");
            return true;
        }else{
            $tpa = $this->sessions->get($sender, "tpa");
            $tpahere = $this->sessions->get($sender, "tpahere");
            
            if(isset($tpa)){
                if(!$tpa instanceof Player || $this->sessions->get($tpa, "tprequest") != $sender){
                    $sender->sendMessage(TextFormat::YELLOW . "You have no active requests");
                }else{
                    $tpa->sendMessage(TextFormat::YELLOW . "$sender accepted your teleport request.\nTeleporting...");
                    $sender->sendMessage(TextFormat::YELLOW . "Teleporting $tpa to you...");
                    $tpa->teleport($sender->getPosition(), $sender->yaw, $sender->pitch);
                    
                    $this->sessions->set($sender, "tpa", null);
                    $this->sessions->set($tpa, "tprequest", null);
                }
            }elseif(isset($tpahere)){
                if(!$tpahere instanceof Player || $this->sessions->get($tpahere, "tprequest") != $sender|"@all"){
                    $sender->sendMessage(TextFormat::YELLOW . "You have no active requests");
                }else{
                    $tpahere->sendMessage(TextFormat::YELLOW . "$sender accepted your teleport request.");
                    $sender->sendMessage(TextFormat::YELLOW . "Teleporting...");
                    
                    $sender->teleport($tpahere->getPosition(), $tpahere->yaw, $tpahere->pitch);
                    $tpahere->sendMessage(TextFormat::YELLOW . "$sender was teleported to you.");
                    
                    $this->sessions->set($sender, "tpahere", null);
                    $this->sessions->set($tpahere, "tprequest", null);
                }
            }else{
                $sender->sendMessage(TextFormat::YELLOW . "You have no active requests");
            }
            return true;
        }
    }
}
