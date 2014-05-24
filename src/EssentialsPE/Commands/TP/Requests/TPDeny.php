<?php
namespace EssentialsPE\Commands\TP\Requests;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
//use pocketmine\Server;
use pocketmine\utils\TextFormat;

use EssentialsPE\API\Sessions;

class TPDeny extends Command{
    public $sessions;
    
    public function __construct() {
        parent::__construct("tpdeny", "Ignore the lastest teleport request", "/tpdeny", ["tpno"]);
        $this->setPermission("essentials.tp.requests.tpdeny");
        
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
                if(!$tpa instanceof Player){
                    $sender->sendMessage(TextFormat::YELLOW . "You have no active requests");
                }else{
                    $sender->sendMessage(TextFormat::YELLOW . "Ignored $tpa's teleport request.");
                    
                    $this->sessions->set($sender, "tpa", null);
                    if($this->sessions->get($tpa, "tpa") == $sender){
                        $this->sessions->set($tpa, "tprequest", null);
                    }
                }
            }elseif(isset($tpahere)){
                if(!$tpahere instanceof Player){
                    $sender->sendMessage(TextFormat::YELLOW . "You have no active requests");
                }else{
                    $sender->sendMessage(TextFormat::YELLOW . "Ignored $tpahere's teleport request.");
                    
                    $this->sessions->set($sender, "tpahere", null);
                    if($this->sessions->get($tpahere, "tpahere") == $sender){
                        $this->sessions->set($tpahere, "tprequest", null);
                    }
                }
            }else{
                $sender->sendMessage(TextFormat::YELLOW . "You have no active requests");
            }
            return true;
        }
    }
}
