<?php
namespace EssentialsPE;

use pocketmine\command\Command;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\Player;
use pocketmine\Server;

abstract class BaseCommand extends Command implements PluginIdentifiableCommand{
    /** @var \pocketmine\plugin\Plugin */
    public $plugin;

    public function getPlugin() {
        return $this->plugin;
    }

    public function isPlayer($player){
        if($player instanceof Player && $player->isOnline()){
            return $player instanceof Player;
        }else{
            return false;
        }
    }

    public function getPlayer($player){
        $r = "";
        if(Server::getInstance()->getPlayerExact($player)){
            $r = Server::getInstance()->getPlayerExact($player);
        }else{
            foreach(Server::getInstance()->getOnlinePlayers() as $p){
                if($p->getDisplayName() == $player){
                    $r = Server::getInstance()->getPlayerExact($p->getName());
                }
            }
        }
        if($r == ""){
            return false;
        }else{
            return $r;
        }
    }
}
