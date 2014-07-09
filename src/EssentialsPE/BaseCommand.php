<?php
namespace EssentialsPE;

use pocketmine\command\Command;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

abstract class BaseCommand extends Command implements PluginIdentifiableCommand, Listener{
    /** @var Loader */
    public $plugin;

    public function __construct(Loader $plugin, $name, $description = "", $usageMessage = null, array $aliases = []){
        parent::__construct($name, $description, $usageMessage, $aliases);
        $this->plugin = $plugin;
    }

    public function getPlugin(){
        return $this->plugin;
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

    public function getPlayer($player){
        $r = "";
        foreach(Server::getInstance()->getOnlinePlayers() as $p){
            if($p->getDisplayName() == $player || $p->getName() == $player){
                $r = Server::getInstance()->getPlayerExact($p->getName());
            }
        }
        if($r == ""){
            return false;
        }else{
            return $r;
        }
    }
} 