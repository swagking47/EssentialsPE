<?php
namespace EssentialsPE;

use EssentialsPE\API\Sessions;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;

use EssentialsPE\Commands\Broadcast;
use EssentialsPE\Commands\Burn;
use EssentialsPE\Commands\ClearInventory;
use EssentialsPE\Commands\Essentials;
use EssentialsPE\Commands\Extinguish;
use EssentialsPE\Commands\GetPos;
use EssentialsPE\Commands\Heal;
use EssentialsPE\Commands\Kickall;
use EssentialsPE\Commands\More;
use EssentialsPE\Commands\Mute;
use EssentialsPE\Commands\Nick;
use EssentialsPE\Commands\RealName;
use EssentialsPE\Commands\Repair;
use EssentialsPE\Commands\Seen;
use EssentialsPE\Commands\Setspawn;

//Events:
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
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
     * @param PlayerChatEvent $event
     *
     * @priority HIGH
     */
    public function onPlayerChat(PlayerChatEvent $event){
        if(Sessions::$instance->sessions[$event->getPlayer()->getName()]["mute"] == true){
            $event->setCancelled();
        }
    }
    
    /**
     * @param PlayerJoinEvent $event
     * 
     * @priority HIGH
     */
    public function onPlayerJoin(PlayerJoinEvent $event){
        Sessions::$instance->sessions[$event->getPlayer()->getName()] = Sessions::$instance->default;
        if(Nick::$instance->config->exists($event->getPlayer()->getName())){
            $event->getPlayer()->setDisplayName(Nick::$instance->config->get($event->getPlayer()->getName()));
        }
    }
    
    /**
     * @param PlayerQuitEvent $event
     */
    public function onPlayerQuit(PlayerQuitEvent $event){
        if(isset(Sessions::$instance->sessions[$event->getPlayer()->getName()])){
            unset(Sessions::$instance->sessions[$event->getPlayer()->getName()]);
        }
    }
    
    public function registerCommands(){
        $fallbackPrefix = "essentials";
        $this->getServer()->getCommandMap()->register($fallbackPrefix, new Broadcast($this));
        $this->getServer()->getCommandMap()->register($fallbackPrefix, new Burn($this));
        $this->getServer()->getCommandMap()->register($fallbackPrefix, new ClearInventory($this));
        $this->getServer()->getCommandMap()->register($fallbackPrefix, new Essentials($this));
        $this->getServer()->getCommandMap()->register($fallbackPrefix, new Extinguish($this));
        $this->getServer()->getCommandMap()->register($fallbackPrefix, new GetPos($this));
        $this->getServer()->getCommandMap()->register($fallbackPrefix, new Heal($this));
        $this->getServer()->getCommandMap()->register($fallbackPrefix, new Kickall($this));
        $this->getServer()->getCommandMap()->register($fallbackPrefix, new More($this));
        $this->getServer()->getCommandMap()->register($fallbackPrefix, new Mute($this));
        $this->getServer()->getCommandMap()->register($fallbackPrefix, new Nick($this));
        $this->getServer()->getCommandMap()->register($fallbackPrefix, new RealName($this));
        $this->getServer()->getCommandMap()->register($fallbackPrefix, new Repair($this));
        $this->getServer()->getCommandMap()->register($fallbackPrefix, new Seen($this));
        //$this->getServer()->getCommandMap()->register($fallbackPrefix, new Setspawn($this));  //Work in Progress, this may not work has desired :P
    }
}
