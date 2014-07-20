<?php
namespace EssentialsPE\Events;

use EssentialsPE\Loader;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityLevelChangeEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\tile\Sign;
use pocketmine\utils\TextFormat;

class EventHandler implements Listener{
    /** @var \EssentialsPE\Loader  */
    public $api;

    public function __construct(Loader $plugin){
        $this->api = $plugin;
    }

    /**
     * @param PlayerPreLoginEvent $event
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
            $event->setCancelled(true);
        }
        $message = $this->api->colorMessage($message, $player);
        if($message === false){
            $event->setCancelled(true);
        }
        $event->setMessage($message);
    }

    /**
     * @param EntityLevelChangeEvent $event
     *
     * @priority HIGHEST
     */
    public function onEntityLevelChange(EntityLevelChangeEvent $event){
        $entity = $event->getEntity();
        $origin = $event->getOrigin();
        $target = $event->getTarget();
        if($entity instanceof Player){
            $this->api->switchLevelVanish($entity, $origin, $target);
        }
    }

    /**
     * @param EntityDamageEvent $event
     *
     * @priority HIGH
     */
    public function onEntityDamage(EntityDamageEvent $event){
        $entity = $event->getEntity();
        if($entity instanceof Player && $this->api->isGod($entity)){
            $event->setCancelled(true);
        }
    }

    /**
     * @param EntityDamageByEntityEvent $event
     */
    public function onEntityDamageByEntity(EntityDamageByEntityEvent $event){
        $victim = $event->getEntity();
        $issuer = $event->getDamager();
        if($victim instanceof Player && $issuer instanceof Player){
            if(!$this->api->isPvPEnabled($victim)){
                $issuer->sendMessage(TextFormat::RED . $victim->getDisplayName() . " have PvP disabled!");
                $event->setCancelled(true);
            }elseif(!$this->api->isPvPEnabled($issuer)){
                $issuer->sendMessage(TextFormat::RED . "You have PvP disabled!");
                $event->setCancelled(true);
            }
        }
    }

    /**
     * @param PlayerInteractEvent $event
     */
    public function onBlockTap(PlayerInteractEvent $event){
        $player = $event->getPlayer();
        $block = $event->getBlock();
        $item = $event->getItem();

        //PowerTool
        if($this->api->isPowerToolEnabled($player)){
            if($this->api->getPowerToolItemCommand($player, $item) !== false){
                Server::getInstance()->dispatchCommand($player, $this->api->getPowerToolItemCommand($player, $item));
                $event->setCancelled(true);
            }
        }
    }

    /**
     * @param BlockPlaceEvent $event
     *
     * @priority HIGH
     */
    public function onBlockPlace(BlockPlaceEvent $event){
        $player = $event->getPlayer();
        $item = $event->getItem();

        if($this->api->isPowerToolEnabled($player)){
            if($this->api->getPowerToolItemCommand($player, $item) !== false){
                Server::getInstance()->dispatchCommand($player, $this->api->getPowerToolItemCommand($player, $item));
                $event->setCancelled(true);
            }
        }
    }
}