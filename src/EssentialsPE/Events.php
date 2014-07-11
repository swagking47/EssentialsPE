<?php
namespace EssentialsPE;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\Server;

class Events implements Listener{
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
        $api = new API();
        if($api->getNick($player) != false){
            $api->setNick($player, $api->getNick($player), false);
        }
    }

    /**
     * @param PlayerJoinEvent $event
     *
     * @priority HIGH
     */
    public function onPlayerJoin(PlayerJoinEvent $event){
        $player = $event->getPlayer();
        $api = new API();

        //Session configure:
        $api->muteSessionCreate($player);
        $api->createSession($player);
        //Join Message (nick):
        $event->setJoinMessage($player->getDisplayName() . " joined the game");
        //Hide vanished players
        foreach(Server::getInstance()->getOnlinePlayers() as $p){
            if($api->isVanished($p)){
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
        $api = new API();

        //Quit message (nick):
        $event->setQuitMessage($player->getDisplayName() . " left the game");
        //Nick and NameTag restore:
        $api->setNick($player, $player->getName(), false);
        //Session destroy:
        $api->removeSession($player);
    }

    /**
     * @param PlayerChatEvent $event
     *
     * @priority HIGH
     */
    public function onPlayerChat(PlayerChatEvent $event){
        $player = $event->getPlayer();
        $message = $event->getMessage();
        $api = new API();
        if($api->isMuted($player)){
            $event->setCancelled();
        }
        $message = $api->colorMessage($message);
        $event->setMessage($message);
    }
}