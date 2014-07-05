<?php
namespace EssentialsPE;

use EssentialsPE\API\Nicks;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\event\player\PlayerQuitEvent;

class Events implements Listener{
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
} 