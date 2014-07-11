<?php
namespace EssentialsPE\Events;

use EssentialsPE\API;
use EssentialsPE\Loader;
use pocketmine\event\entity\EntityLevelChangeEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\Player;
use pocketmine\Server;

class EventHandler implements Listener{
    /** @var  \EssentialsPE\API */
    public $api;

    public function __construct(Loader $plugin){
        $this->api = new API($plugin);
    }

    /**
     * @param PlayerPreLoginEvent $event
     *
     * @priority HIGHEST
     */
    public function onPlayerPreLogin(PlayerPreLoginEvent $event){
        $player = $event->getPlayer();

        //Ban remove:
        if($player->isBanned() && $player->hasPermission("essentials.ban.exempt")){
            $player->setBanned(false);
        }
        //Nick and NameTag set:
        if($this->api->getNick($player) != false){
            $this->api->setNick($player, $this->api->getNick($player), false);
        }
    }

    /**
     * @param PlayerJoinEvent $event
     *
     * @priority HIGH
     */
    public function onPlayerJoin(PlayerJoinEvent $event){
        $player = $event->getPlayer();

        //Session configure:
        $this->api->muteSessionCreate($player);
        $this->api->createSession($player);
        //Join Message (nick):
        $event->setJoinMessage($player->getDisplayName() . " joined the game");
        //Hide vanished players
        foreach(Server::getInstance()->getOnlinePlayers() as $p){
            if($this->api->isVanished($p)){
                $player->hidePlayer($p);
            }
        }
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
        //Nick and NameTag restore:
        $this->api->setNick($player, $player->getName(), false);
        //Session destroy:
        $this->api->removeSession($player);
    }

    /**
     * @param PlayerChatEvent $event
     *
     * @priority HIGH
     */
    public function onPlayerChat(PlayerChatEvent $event){
        $player = $event->getPlayer();
        $message = $event->getMessage();
        if($this->api->isMuted($player)){
            $event->setCancelled();
        }
        $message = $this->api->colorMessage($message);
        $event->setMessage($message);
    }

    public function onEntityLevelChange(EntityLevelChangeEvent $event){
        $entity = $event->getEntity();
        $origin = $event->getOrigin();
        $target = $event->getTarget();
        if($entity instanceof Player){
            $this->api->switchLevelVanish($entity, $origin, $target);
        }
    }
}