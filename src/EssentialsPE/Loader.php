<?php
namespace EssentialsPE;

use EssentialsPE\API\Sessions;
use EssentialsPE\Commands\Broadcast;
use EssentialsPE\Commands\Burn;
use EssentialsPE\Commands\ClearInventory;
use EssentialsPE\Commands\Essentials;
use EssentialsPE\Commands\Extinguish;
use EssentialsPE\Commands\GetPos;
use EssentialsPE\Commands\Heal;
use EssentialsPE\Commands\KickAll;
use EssentialsPE\Commands\More;
use EssentialsPE\Commands\Mute;
use EssentialsPE\Commands\Nick;
use EssentialsPE\Commands\RealName;
use EssentialsPE\Commands\Repair;
use EssentialsPE\Commands\Seen;
//use EssentialsPE\Commands\Setspawn;

//Events:
use EssentialsPE\Commands\Setspawn;
use EssentialsPE\Commands\Vanish;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;

class Loader extends PluginBase implements Listener{
	/** @var BaseCommand[] */
	private $cmds = [];
	/** @var Sessions */
	private $sessions;
    public function onLoad() {
        @mkdir("plugins/Essentials/");
    }
    public function onEnable() {
	    $this->getLogger()->info(TextFormat::YELLOW . "Loading...");
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
	    $this->sessions = new Sessions();
        $this->registerCommands();
    }
    /**
     * @param PlayerChatEvent $event
     *
     * @priority HIGH
     */
    public function onPlayerChat(PlayerChatEvent $event){
        if($this->sessions->sessions[$event->getPlayer()->getName()]["mute"] == true){
            $event->setCancelled();
        }
    }
    /**
     * @param PlayerJoinEvent $event
     * 
     * @priority HIGH
     */
    public function onPlayerJoin(PlayerJoinEvent $event){
        $this->sessions->sessions[$event->getPlayer()->getName()] = $this->sessions->default;
        if($this->cmds["nick"]->config->exists($event->getPlayer()->getName())){
            $event->getPlayer()->setDisplayName($this->cmds["nick"]->config->get($event->getPlayer()->getName()));
        }
    }
    /**
     * @param PlayerQuitEvent $event
     */
    public function onPlayerQuit(PlayerQuitEvent $event){
        if(isset($this->sessions->sessions[$event->getPlayer()->getName()])){
            unset($this->sessions->sessions[$event->getPlayer()->getName()]);
        }
    }
    public function registerCommands(){
        $fallbackPrefix = "essentials";
        $this->getServer()->getCommandMap()->register($fallbackPrefix, $this->cmds["broadcast"] = new Broadcast($this));
        $this->getServer()->getCommandMap()->register($fallbackPrefix, $this->cmds["burn"] = new Burn($this));
        $this->getServer()->getCommandMap()->register($fallbackPrefix, $this->cmds["clearinventory"] = new ClearInventory($this));
        $this->getServer()->getCommandMap()->register($fallbackPrefix, $this->cmds["essentials"] = new Essentials($this));
        $this->getServer()->getCommandMap()->register($fallbackPrefix, $this->cmds["extinguish"] = new Extinguish($this));
        $this->getServer()->getCommandMap()->register($fallbackPrefix, $this->cmds["getpos"] = new GetPos($this));
        $this->getServer()->getCommandMap()->register($fallbackPrefix, $this->cmds["heal"] = new Heal($this));
        $this->getServer()->getCommandMap()->register($fallbackPrefix, $this->cmds["kickall"] = new KickAll($this));
        $this->getServer()->getCommandMap()->register($fallbackPrefix, $this->cmds["more"] = new More($this));
        $this->getServer()->getCommandMap()->register($fallbackPrefix, $this->cmds["mute"] = new Mute($this)); //Work in Progress...
        $this->getServer()->getCommandMap()->register($fallbackPrefix, $this->cmds["nick"] = new Nick($this)); //Work in Progress...
        $this->getServer()->getCommandMap()->register($fallbackPrefix, $this->cmds["realname"] = new RealName($this));
        $this->getServer()->getCommandMap()->register($fallbackPrefix, $this->cmds["repair"] = new Repair($this));
        $this->getServer()->getCommandMap()->register($fallbackPrefix, $this->cmds["seen"] = new Seen($this));
        $this->getServer()->getCommandMap()->register($fallbackPrefix, $this->cmds["setspawn"] = new Setspawn($this));  //Work in Progress, this may not work has desired :P
        $this->getServer()->getCommandMap()->register($fallbackPrefix, $this->cmds["vanish"] = new Vanish($this)); //Work in Progress...
    }
	public function getCommand($cmd){
		return isset($this->cmds[$cmd]) ? $this->cmds[$cmd]:false;
	}
}
