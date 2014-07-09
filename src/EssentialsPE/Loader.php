<?php
namespace EssentialsPE;

use EssentialsPE\API\Nicks;
use EssentialsPE\Commands\Broadcast;
use EssentialsPE\Commands\Burn;
use EssentialsPE\Commands\ClearInventory;
use EssentialsPE\Commands\DefaultCommands\Me;
use EssentialsPE\Commands\Essentials;
use EssentialsPE\Commands\Extinguish;
use EssentialsPE\Commands\GetPos;
use EssentialsPE\Commands\Heal;
use EssentialsPE\Commands\KickAll;
use EssentialsPE\Commands\More;
use EssentialsPE\Commands\Mute;
use EssentialsPE\Commands\Nick; //Use DIRECTORY
use EssentialsPE\Commands\RealName;
use EssentialsPE\Commands\Repair;
use EssentialsPE\Commands\Seen;
use EssentialsPE\Commands\SetSpawn;
use EssentialsPE\Commands\Top;
use EssentialsPE\Commands\Vanish;
use EssentialsPE\Commands\Warps\RemoveWarp;
use EssentialsPE\Commands\Warps\SetWarp;
use EssentialsPE\Commands\Warps\Warp;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use pocketmine\event\Listener;

class Loader extends PluginBase implements Listener{
    const DIRECTORY = "plugins/EssentialsPE/";

    public function onEnable() {
        @mkdir(Loader::DIRECTORY);
	    $this->getLogger()->info(TextFormat::YELLOW . "Loading...");
        $this->getServer()->getPluginManager()->registerEvents(new Events(), $this);
        $this->registerCommands();

        foreach($this->getServer()->getOnlinePlayers() as $p){
            $nick = new Nicks($p);
            $nick->set($nick->get(), false);
        }
    }

    public function onDisable(){
        foreach($this->getServer()->getOnlinePlayers() as $p){
            $nick = new Nicks($p);
            $nick->set($p->getName(), false);
        }
    }

    private function registerCommands(){
        $fallbackPrefix = "EssentialsPE";
        $this->getServer()->getCommandMap()->register($fallbackPrefix, new Broadcast($this));
        $this->getServer()->getCommandMap()->register($fallbackPrefix, new Burn($this));
        $this->getServer()->getCommandMap()->register($fallbackPrefix, new ClearInventory($this));
        $this->getServer()->getCommandMap()->register($fallbackPrefix, new Essentials($this));
        $this->getServer()->getCommandMap()->register($fallbackPrefix, new Extinguish($this));
        $this->getServer()->getCommandMap()->register($fallbackPrefix, new GetPos($this));
        $this->getServer()->getCommandMap()->register($fallbackPrefix, new Heal($this));
        $this->getServer()->getCommandMap()->register($fallbackPrefix, new KickAll($this));
        $this->getServer()->getCommandMap()->register($fallbackPrefix, new More($this));
        //$this->getServer()->getCommandMap()->register($fallbackPrefix, new Mute($this)); //TODO
        $this->getServer()->getCommandMap()->register($fallbackPrefix, new Nick($this));
        $this->getServer()->getCommandMap()->register($fallbackPrefix, new RealName($this));
        $this->getServer()->getCommandMap()->register($fallbackPrefix, new Repair($this));
        $this->getServer()->getCommandMap()->register($fallbackPrefix, new Seen($this));
        $this->getServer()->getCommandMap()->register($fallbackPrefix, new SetSpawn($this));
        $this->getServer()->getCommandMap()->register($fallbackPrefix, new Top($this));
        //$this->getServer()->getCommandMap()->register($fallbackPrefix, new Vanish($this)); //TODO

        //Warps:
        //$this->getServer()->getCommandMap()->register($fallbackPrefix, new RemoveWarp($this)); //TODO
        //$this->getServer()->getCommandMap()->register($fallbackPrefix, new SetWarp($this)); //TODO
        //$this->getServer()->getCommandMap()->register($fallbackPrefix, new Warp($this)); //TODO

        //Default Commands:
        $this->getServer()->getCommandMap()->register($fallbackPrefix, new Me($this)); //TODO
    }
}