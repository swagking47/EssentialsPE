<?php
namespace EssentialsPE;

use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

use EssentialsPE\Commands\AFK;
use EssentialsPE\Commands\Back;
use EssentialsPE\Commands\Broadcast;
use EssentialsPE\Commands\Burn;
use EssentialsPE\Commands\Essentials;
use EssentialsPE\Commands\Extinguish;
use EssentialsPE\Commands\GetPos;
use EssentialsPE\Commands\God;
use EssentialsPE\Commands\Heal;
use EssentialsPE\Commands\More;
use EssentialsPE\Commands\Mute;
use EssentialsPE\Commands\Nick;
use EssentialsPE\Commands\PvP;
use EssentialsPE\Commands\RealName;
use EssentialsPE\Commands\Repair;
use EssentialsPE\Commands\Seen;
use EssentialsPE\Commands\Setspawn;
use EssentialsPE\Commands\Vanish;

//Teleport:
use EssentialsPE\Commands\TP\TPAll;
use EssentialsPE\Commands\TP\TPHere;
    //Requests
    use EssentialsPE\Commands\TP\Requests\TPA;
    use EssentialsPE\Commands\TP\Requests\TPAAll;
    use EssentialsPE\Commands\TP\Requests\TPAHere;
    use EssentialsPE\Commands\TP\Requests\TPAccept;
    use EssentialsPE\Commands\TP\Requests\TPDeny;

//Events:
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use EssentialsPE\API\Sessions;

class Loader extends PluginBase implements Listener{
    public function onLoad() {
        console(TextFormat::YELLOW . "Loading EssentialsPE plugin...");
        @mkdir("plugins/Essentials/");
    }
    
    public function onEnable() {
        Server::getInstance()->getPluginManager()->registerEvents($this, $this);
        
        $fallbackPrefix = "essentials";
        //Server::getInstance()->getCommandMap()->register($fallbackPrefix, new AFK()); //Work in Progress, this may not work has desired :P
        //Server::getInstance()->getCommandMap()->register($fallbackPrefix, new Back()); //Work in Progress, this may not work has desired :P
        Server::getInstance()->getCommandMap()->register($fallbackPrefix, new Broadcast());
        Server::getInstance()->getCommandMap()->register($fallbackPrefix, new Burn());
        Server::getInstance()->getCommandMap()->register($fallbackPrefix, new Essentials());
        Server::getInstance()->getCommandMap()->register($fallbackPrefix, new Extinguish());
        Server::getInstance()->getCommandMap()->register($fallbackPrefix, new GetPos());
        //Server::getInstance()->getCommandMap()->register($fallbackPrefix, new God()); //Work in Progress, this may not work has desired :P
        Server::getInstance()->getCommandMap()->register($fallbackPrefix, new Heal());
        //Server::getInstance()->getCommandMap()->register($fallbackPrefix, new More()); //Work in Progress, this may not work has desired :P
        Server::getInstance()->getCommandMap()->register($fallbackPrefix, new Mute());
        Server::getInstance()->getCommandMap()->register($fallbackPrefix, new Nick());
        //Server::getInstance()->getCommandMap()->register($fallbackPrefix, new PvP()); //Work in Progress, this may not work has desired :P
        Server::getInstance()->getCommandMap()->register($fallbackPrefix, new RealName());
        Server::getInstance()->getCommandMap()->register($fallbackPrefix, new Repair()); //Work in Progress, this may not work has desired :P
        Server::getInstance()->getCommandMap()->register($fallbackPrefix, new Seen());
        //Server::getInstance()->getCommandMap()->register($fallbackPrefix, new Setspawn());  //Work in Progress, this may not work has desired :P
        //Server::getInstance()->getCommandMap()->register($fallbackPrefix, new Vanish());  //Work in Progress, this may not work has desired :P
        
        //Teleport: (//Work in Progress, this may not work has desired :P)
        /*Server::getInstance()->getCommandMap()->register($fallbackPrefix, new TPAll());

        Server::getInstance()->getCommandMap()->register($fallbackPrefix, new TPHere());
            //Requests:
            Server::getInstance()->getCommandMap()->register($fallbackPrefix, new TPA());
            Server::getInstance()->getCommandMap()->register($fallbackPrefix, new TPAAll());
            Server::getInstance()->getCommandMap()->register($fallbackPrefix, new TPAHere());
            Server::getInstance()->getCommandMap()->register($fallbackPrefix, new TPAccept());
            Server::getInstance()->getCommandMap()->register($fallbackPrefix, new TPDeny());*/
    }
    
    /**
     * @param PlayerChatEvent $event
     * 
     * @priority HIGH
     * @ignoreCancelled true
     */
    public function PlayerChat(PlayerChatEvent $event){
        if(Mute::get($event->getPlayer()) != false){
            $event->setCancelled();
        }
        
        if(strpos($event->getMessage(), "&") != false){
            if(!$event->getPlayer()->hasPermission("essentials.colorchat")){
                $event->setCancelled();
                $event->getPlayer()->sendMessage(TextFormat::RED . "You can't chat in color.");
            }else{
                str_replace("&", "§", $event->getMessage()); //TODO Implement this with /say
            }
        }
    }
    
    /**
     * @param PlayerJoinEvent $event
     * 
     * @priority HIGH
     * @ignoreCancelled false
     */
    public function PlayerJoin(PlayerJoinEvent $event){
        //Sessions create
        $sessions = new Sessions();
        $sessions->create($event->getPlayer());
    }
    
    /**
     * @param PlayerQuitEvent $event
     * 
     * @priority HIGH
     * @ignoreCancelled false
     */
    public function PlayerQuit(PlayerQuitEvent $event){
        //Sessions destroy
        $sessions = new Sessions();
        $sessions->destroy($event->getPlayer());
    }
}
