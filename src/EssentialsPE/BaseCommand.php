<?php
namespace EssentialsPE;

use pocketmine\command\Command;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\Player;

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
}
