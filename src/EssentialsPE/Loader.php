<?php
namespace EssentialsPE;

use EssentialsPE\API\Nicks;
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
use EssentialsPE\Commands\SetSpawn;
use EssentialsPE\Commands\Top;
use EssentialsPE\Commands\Vanish;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\level\Position;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;

class Loader extends PluginBase implements Listener{
    const DIRECTORY = "plugins/Essentials/";

    //Sessions:
    public $defaults = [
        "mute" => false,
        "vanish" => false
    ];
    public $sessions = [];

    public function onEnable() {
        @mkdir(Loader::DIRECTORY);
	    $this->getLogger()->info(TextFormat::YELLOW . "Loading...");
        $this->registerEvents();
        $this->registerCommands();
    }

    /**
     * @param PlayerPreLoginEvent $event
     *
     * @priority HIGHEST
     */
    public function onPlayerPreLogin(PlayerPreLoginEvent $event){
        //Ban remove:
        $player = $event->getPlayer();
        if($player->isBanned() && $player->hasPermission("essentials.command.ban.exempt")){
            $player->setBanned(false);
        }
        //Nick and NameTags:
        $Nick = new Nicks($event->getPlayer());
        if($Nick->get() != false){
            $Nick->set($Nick->get(), false);
        }
    }

    /**
     * @param PlayerJoinEvent $event
     *
     * @priority HIGH
     */
    public function onPlayerJoin(PlayerJoinEvent $event){
        $player = $event->getPlayer();
        //Join Message (nick):
        $event->setJoinMessage($player->getDisplayName() . " joined the game");
    }

    /**
     * @param PlayerQuitEvent $event
     *
     * @priority HIGH
     */
    public function onPlayerQuit(PlayerQuitEvent $event){
        $player = $event->getPlayer();
        //Quit message (nick):
        $event->setQuitMessage($player->getDisplayName() . " left the game");
    }

    private function registerEvents(){
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
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
        $this->getServer()->getCommandMap()->register($fallbackPrefix, new Top($this)); //TODO
        //$this->getServer()->getCommandMap()->register($fallbackPrefix, new Vanish($this)); //TODO
    }
}