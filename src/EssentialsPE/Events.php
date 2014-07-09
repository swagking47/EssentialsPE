<?php
namespace EssentialsPE;

use EssentialsPE\API\Nicks;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

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
        //Nick and NameTag restore:
        $Nick = new Nicks($player);
        $Nick->set($player->getName(), false);
    }

    /**
     * @param PlayerChatEvent $event
     *
     * @priority HIGH
     */
    public function onPlayerChat(PlayerChatEvent $event){
        $player = $event->getPlayer();
        $message = $event->getMessage();
        $event->setMessage($this->colorMessage($player, $message));
    }

    public function colorMessage(Player $player, $message){
        if(!$player->hasPermission("essentials.colorchat")){
            $player->sendMessage(TextFormat::RED . "You don't have permission to use colors in-chat.");
            return false;
        }
        $message = str_replace("&0", "§0", $message);
        $message = str_replace("&1", "§1", $message);
        $message = str_replace("&2", "§2", $message);
        $message = str_replace("&3", "§3", $message);
        $message = str_replace("&4", "§4", $message);
        $message = str_replace("&5", "§5", $message);
        $message = str_replace("&6", "§6", $message);
        $message = str_replace("&7", "§7", $message);
        $message = str_replace("&8", "§8", $message);
        $message = str_replace("&9", "§9", $message);
        $message = str_replace("&a", "§a", $message);
        $message = str_replace("&b", "§b", $message);
        $message = str_replace("&c", "§c", $message);
        $message = str_replace("&d", "§d", $message);
        $message = str_replace("&e", "§e", $message);
        $message = str_replace("&f", "§f", $message);
        $message = str_replace("&k", "§k", $message);
        $message = str_replace("&l", "§l", $message);
        $message = str_replace("&m", "§m", $message);
        $message = str_replace("&n", "§n", $message);
        $message = str_replace("&o", "§o", $message);
        $message = str_replace("&r", "§r", $message);
        return $message;
    }
} 