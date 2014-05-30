<?php
namespace EssentialsPE;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;

use EssentialsPE\Commands\Broadcast;
use EssentialsPE\Commands\Burn;
use EssentialsPE\Commands\Essentials;
use EssentialsPE\Commands\Extinguish;
use EssentialsPE\Commands\GetPos;
use EssentialsPE\Commands\Heal;
use EssentialsPE\Commands\More;
//use EssentialsPE\Commands\Mute;
use EssentialsPE\Commands\Nick;
use EssentialsPE\Commands\RealName;
use EssentialsPE\Commands\Repair;
use EssentialsPE\Commands\Seen;
//use EssentialsPE\Commands\Setspawn;

//Events:
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
//use EssentialsPE\API\Sessions;

class Loader extends PluginBase implements Listener{
    public function onLoad() {
        $this->getLogger()->info(TextFormat::YELLOW . "Loading...");
        @mkdir("plugins/Essentials/");
    }
    
    public function onEnable() {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->registerCommands();
    }

    /**
     * @param PlayerCommandPreprocessEvent $event
     */
    //TODO Colored chat for "/me" and "/say" (Console)
    public function onPlayerChat(PlayerCommandPreprocessEvent $event){
        if(strstr($event->getMessage(), "&") != false){
            if(!$event->getPlayer()->hasPermission("essentials.colorchat")){
                $event->setCancelled();
                $event->getPlayer()->sendMessage(TextFormat::RED . "You can't chat in color.");
            }else{
                $event->setMessage(str_replace("&", "§", $event->getMessage()));
            }
        }
    }
    
    /**
     * @param PlayerJoinEvent $event
     * 
     * @priority HIGHEST
     */
    public function onPlayerJoin(PlayerJoinEvent $event){
        if(Nick::$instance->config->exists($event->getPlayer()->getName())){
            $event->getPlayer()->setDisplayName(Nick::$instance->config->get($event->getPlayer()->getName()));
        }
    }
    
    /**
     * @param PlayerQuitEvent $event
     * 
     * @priority LOWEST
     */
    public function onPlayerQuit(PlayerQuitEvent $event){/*if(isset(Sessions::getInstance()->sessions[$event->getPlayer()])){unset(Sessions::getInstance()->sessions[$event->getPlayer()]);}*/}
    
    public function registerCommands(){
        $fallbackPrefix = "essentials";
        $this->getServer()->getCommandMap()->register($fallbackPrefix, new Broadcast($this));
        $this->getServer()->getCommandMap()->register($fallbackPrefix, new Burn($this));
        $this->getServer()->getCommandMap()->register($fallbackPrefix, new Essentials($this));
        $this->getServer()->getCommandMap()->register($fallbackPrefix, new Extinguish($this));
        $this->getServer()->getCommandMap()->register($fallbackPrefix, new GetPos($this));
        $this->getServer()->getCommandMap()->register($fallbackPrefix, new Heal($this));
        $this->getServer()->getCommandMap()->register($fallbackPrefix, new More($this));
        //$this->getServer()->getCommandMap()->register($fallbackPrefix, new Mute($this));
        $this->getServer()->getCommandMap()->register($fallbackPrefix, new Nick($this));
        $this->getServer()->getCommandMap()->register($fallbackPrefix, new RealName($this));
        $this->getServer()->getCommandMap()->register($fallbackPrefix, new Repair($this));
        $this->getServer()->getCommandMap()->register($fallbackPrefix, new Seen($this));
        //$this->getServer()->getCommandMap()->register($fallbackPrefix, new Setspawn($this));  //Work in Progress, this may not work has desired :P
    }
}
