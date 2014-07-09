<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class Mute extends BaseCommand{
    /**
     * Information on this array is set like:
     * Player Name => boolean (true/false)
     *
     * This is to help switching mute status, may change in the future
     *
     * @var array
     */
    public $mutes = [];

    public function __construct(Loader $plugin){
        parent::__construct($plugin, "mute", "Prevent a player from chatting", "/mute <player>", ["silence"]);
        $this->setPermission("essentials.command.mute");
        Server::getInstance()->getPluginManager()->registerEvents($this, $plugin);
    }

    public function execute(CommandSender $sender, $alias, array $args){
        if(!$this->testPermission($sender)){
            return false;
        }
        if(count($args) != 1){
            $sender->sendMessage(TextFormat::RED . "Usage: " . $this->getUsage());
            return false;
        }
        $player = $this->getPlayer($args[0]);
        if($player == false){
            $sender->sendMessage(TextFormat::RED . "[Error] Player not found.");
        }else{
            if($player->hasPermission("essentials.command.mute.exempt")){
                $sender->sendMessage(TextFormat::RED . "$args[0] can't be muted");
                return false;
            }
            $this->switchMute($player);
            if(!$this->isMuted($player)){
                $sender->sendMessage(TextFormat::YELLOW . "$args[0] has been unmuted!");
            }else{
                $sender->sendMessage(TextFormat::YELLOW . "$args[0] has been muted!");
            }
        }
        return true;
    }

    public function switchMute(Player $player){
        if($this->mutes[$player->getName()] == false){
            $this->mutes[$player->getName()] = true;
        }else{
            $this->mutes[$player->getName()] = false;
        }
    }

    public function isMuted(Player $player){
        if($this->mutes[$player->getName()] != false){
            return true;
        }else{
            return false;
        }
    }

    /**
     * @param PlayerChatEvent $event
     * @return bool
     *
     * @priority HIGH
     * @ignoreCancelled false
     */
    public function onPlayerChat(PlayerChatEvent $event){
        /*if($this->isMuted($event->getPlayer())){
            $event->setCancelled();
        }*/
        $event->setCancelled();
    }
} 