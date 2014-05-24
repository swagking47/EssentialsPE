<?php
namespace EssentialsPE;

use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

use EssentialsPE\Commands\Broadcast;
use EssentialsPE\Commands\Burn;
use EssentialsPE\Commands\Essentials;
use EssentialsPE\Commands\Extinguish;
use EssentialsPE\Commands\GetPos;
use EssentialsPE\Commands\Heal;
use EssentialsPE\Commands\More;
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

class Loader extends PluginBase implements Listener{
    public function onLoad() {
        console(TextFormat::YELLOW . "Loading EssentialsPE plugin...");
        @mkdir("plugins/Essentials/");
    }
    
    public function onEnable() {
        Server::getInstance()->getPluginManager()->registerEvents($this, $this);
        
        $fallbackPrefix = "essentials";
        Server::getInstance()->getCommandMap()->register($fallbackPrefix, new Broadcast($this));
        Server::getInstance()->getCommandMap()->register($fallbackPrefix, new Burn($this));
        Server::getInstance()->getCommandMap()->register($fallbackPrefix, new Essentials($this));
        Server::getInstance()->getCommandMap()->register($fallbackPrefix, new Extinguish($this));
        Server::getInstance()->getCommandMap()->register($fallbackPrefix, new GetPos($this));
        Server::getInstance()->getCommandMap()->register($fallbackPrefix, new Heal($this));
        //Server::getInstance()->getCommandMap()->register($fallbackPrefix, new More($this)); //Work in Progress, this may not work has desired :P
        Server::getInstance()->getCommandMap()->register($fallbackPrefix, new Nick($this));
        Server::getInstance()->getCommandMap()->register($fallbackPrefix, new RealName($this));
        Server::getInstance()->getCommandMap()->register($fallbackPrefix, new Repair($this)); //Work in Progress, this may not work has desired :P
        Server::getInstance()->getCommandMap()->register($fallbackPrefix, new Seen($this));
        //Server::getInstance()->getCommandMap()->register($fallbackPrefix, new Setspawn($this));  //Work in Progress, this may not work has desired :P
        
    }
    
    /**
     * @param PlayerChatEvent $event
     * 
     * @priority HIGH
     * @ignoreCancelled false
     */
    public function onPlayerChat(PlayerChatEvent $event){
        if(strstr($event->getMessage(), "&") != false){
            if(!$event->getPlayer()->hasPermission("essentials.colorchat")){
                $event->setCancelled();
                $event->getPlayer()->sendMessage(TextFormat::RED . "You can't chat in color.");
            }else{
                $message = str_replace("&", "§", $event->getMessage()); //TODO Implement this with /say
                $event->setMessage($message);
            }
        }
    }
    
    /**
     * @param PlayerJoinEvent $event
     * 
     * @priority HIGH
     * @ignoreCancelled false
     */
    public function onPlayerJoin(PlayerJoinEvent $event){
    }
    
    /**
     * @param PlayerQuitEvent $event
     * 
     * @priority HIGH
     * @ignoreCancelled false
     */
    public function onPlayerQuit(PlayerQuitEvent $event){
    }
}
