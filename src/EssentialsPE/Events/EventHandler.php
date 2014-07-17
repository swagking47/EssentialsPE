<?php
namespace EssentialsPE\Events;

use EssentialsPE\Loader;
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
    public $plugin;

    public function __construct(Loader $plugin){
        $this->getAPI() = $plugin;
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
        if($this->getAPI()->getNick($player) != false){
            $this->getAPI()->setNick($player, $this->getAPI()->getNick($player), false);
        }
    }

    /**
     * @param PlayerJoinEvent $event
     */
    public function onPlayerJoin(PlayerJoinEvent $event){
        $player = $event->getPlayer();

        //Session configure:
        $this->getAPI()->muteSessionCreate($player);
        $this->getAPI()->createSession($player);
        //Join Message (nick):
        $event->setJoinMessage($player->getDisplayName() . " joined the game");
        //Hide vanished players
        foreach(Server::getInstance()->getOnlinePlayers() as $p){
            if($this->getAPI()->isVanished($p)){
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
        $this->getAPI()->setNick($player, $player->getName(), false);
        //Session destroy:
        $this->getAPI()->removeSession($player);
    }

    /**
     * @param PlayerChatEvent $event
     *
     * @priority HIGH
     */
    public function onPlayerChat(PlayerChatEvent $event){
        $player = $event->getPlayer();
        $message = $event->getMessage();
        if($this->getAPI()->isMuted($player)){
            $event->setCancelled(true);
        }
        $message = $this->getAPI()->colorMessage($message, $player);
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
            $this->getAPI()->switchLevelVanish($entity, $origin, $target);
        }
    }

    /**
     * @param EntityDamageEvent $event
     *
     * @priority HIGH
     */
    public function onEntityDamage(EntityDamageEvent $event){
        $entity = $event->getEntity();
        if($entity instanceof Player && $this->getAPI()->isGod($entity)){
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
            if(!$this->getAPI()->isPvPEnabled($victim)){
                $issuer->sendMessage(TextFormat::RED . $victim->getDisplayName() . " have PvP disabled!");
                $event->setCancelled(true);
            }elseif(!$this->getAPI()->isPvPEnabled($issuer)){
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
        if($this->getAPI()->isPowerToolEnabled($player)){
            if($this->getAPI()->getPowerToolItemCommand($player, $item) !== false){
                Server::getInstance()->dispatchCommand($player, $this->getAPI()->getPowerToolItemCommand($player, $item));
                $event->setCancelled(true);
            }
        }

        //SignRegister
        if($block instanceof Sign){
            $text = $block->getText();
            if(!$this->getAPI()->getSignRegisterState($player)){
                //Teleports...
                if($text[0] == TextFormat::LIGHT_PURPLE . "[Warp]"){ //Warp
                    $player->sendMessage(TextFormat::YELLOW . "Teleporting to warp: $text[1]");
                    $this->getAPI()->tpWarp($player, $text[1]);
                }elseif($text[0] == TextFormat::LIGHT_PURPLE . "[Teleport]"){ //Teleport
                    $player->sendMessage(TextFormat::YELLOW . "Teleporting...");
                    $player->teleport(new Vector3($text[1], $text[2], $text[3]));
                }
            }else{
                //Register
                if($this->getAPI()->getSignRegisterState($player) == "warp"){
                    $text[0] = TextFormat::LIGHT_PURPLE . "[Warp]";
                    $text[1] = $this->getAPI()->getWarpSignRegister($player);
                    $this->getAPI()->disableWarpSignRegistration($player);
                }elseif($this->getAPI()->getSignRegisterState($player) == "teleport"){
                    $text[0] = TextFormat::LIGHT_PURPLE . "[Teleport]";
                    $coords = $this->getAPI()->getTPSignRegister($player);
                    $text[1] = $coords->getX();
                    $text[2] = $coords->getY();
                    $text[3] = $coords->getZ();
                }
                $block->scheduleUpdate();
            }
        }
    }
}