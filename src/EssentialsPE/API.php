<?php
namespace EssentialsPE;

use EssentialsPE\Events\PlayerNickChangeEvent;
use EssentialsPE\Loader;
use pocketmine\level\Level;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;

class API {
    public $plugin;

    public function __construct(Loader $plugin){
        $this->plugin = $plugin;
    }

    public function colorMessage($message){
        $search = ["&0", "&1", "&2", "&3", "&4", "&5", "&6", "&7", "&8", "&9", "&a", "&b", "&c", "&d", "&e", "&f", "&k", "&l", "&m", "&n", "&o", "&r"];
        //$formats = ["§0", "§1", "§2", "§3", "§4", "§5", "§6", "§7", "§8", "§9", "§a", "§b", "§c", "§d", "§e", "§f", "§k", "§l", "§m", "§n", "§o", "§r"];
        foreach($search as $s){
            $code = substr($s, -1, 1);
            $message = str_replace($s, "§" . $code, $message);
        }
        return $message;
    }
    //Sessions
    private $default = [
        "god" => false,
        "pvp" => false,
        "signregister" => false,
        "vanish" => false
    ];

    public function createSession(Player $player){
        $GLOBALS["sessions"][$player->getName()] = $this->default;
    }

    public function removeSession(Player $player){
        unset($GLOBALS["sessions"][$player->getName()]);
    }

    public function setSession(Player $player, $key, $value){
        if(!(isset($GLOBALS["sessions"][$player->getName()]) || isset($GLOBALS["sessions"][$player->getName()][$key]))){
            return false;
        }
        $GLOBALS["sessions"][$player->getName()][$key] = $value;
        return true;
    }

    public function getSession(Player $player, $key){
        if(!(isset($GLOBALS["sessions"][$player->getName()]) || isset($GLOBALS["sessions"][$player->getName()][$key]))){
            return false;
        }
        return $GLOBALS["sessions"][$player->getName()][$key];
    }

    public function muteSessionCreate(Player $player){
        if(!isset($GLOBALS["mutes"][$player->getName()])){
            $GLOBALS["mutes"][$player->getName()] = false;
        }
    }

    public function signregisterSessionCreate(Player $player){
        if(!isset($GLOBALS["signregister"][$player->getName()])){
            $GLOBALS["signregister"][$player->getName()] = false;
        }
    }

    //Home
    public function setHome(Player $player, $home_name){
        $config = new Config(Loader::DIRECTORY . $player->getName() . ".yml");
        if(!$config->exists($home_name)){
            if(!$player->hasPermission("essentials.home." . ($this->countHomes($player) + 1))){
                return false;
            }
            $pos = array();
            $pos["x"] = $player->getX();
            $pos["y"] = $player->getY();
            $pos["z"] = $player->getZ();
            $pos["yaw"] = $player->yaw;
            $pos["pitch"] = $player->pitch;
            $pos["level"] = $player->getLevel()->getName();
            $config->set($home_name, $pos);
        }
        return true;
    }

    public function homeTp(Player $player, $home_name){
        $config = new Config(Loader::DIRECTORY . $player->getName() . ".yml");
        if(!$config->exists($home_name)){
            return false;
        }
        $home = $config->get($home_name);
        if($player->getLevel()->getName() != $home["level"]){
            $player->setLevel($home["level"]);
        }
        $player->teleport(new Vector3($home["x"], $home["y"], $home["z"]), $home["yaw"], $home["pitch"]);
        return true;
    }

    private function countHomes(Player $player){
        $config = new Config(Loader::DIRECTORY . $player->getName() . ".yml");
        return count($config->getAll());
    }

    //Mute
    public function switchMute(Player $player){
        if($GLOBALS["mutes"][$player->getName()] == false){
            $GLOBALS["mutes"][$player->getName()] = true;
        }else{
            $GLOBALS["mutes"][$player->getName()] = false;
        }
    }

    public function isMuted(Player $player){
        if($GLOBALS["mutes"][$player->getName()] == false){
            return false;
        }else{
            return true;
        }
    }

    //Nick
    public function setNick(Player $player, $nick, $save = true){
        $config = new Config(Loader::DIRECTORY . "Nicks.yml", Config::YAML);
        Server::getInstance()->getPluginManager()->callEvent(new PlayerNickChangeEvent($this->plugin, $player, $nick, $player->getDisplayName()));
        $nick = $nick . TextFormat::RESET;
        $player->setNameTag($nick);
        $player->setDisplayName($nick);
        if($save == true){
            $config->set($player->getName(), $nick);
            $config->save();
        }
        return true;
    }

    public function removeNick(Player $player, $nick, $save = true){
        $config = new Config(Loader::DIRECTORY . "Nicks.yml", Config::YAML);
        Server::getInstance()->getPluginManager()->callEvent(new PlayerNickChangeEvent($this->plugin, $player, $nick, $player->getDisplayName()));
        $player->setNameTag($player->getName());
        $player->setDisplayName($player->getName());
        if($save === true){
            $config->remove($player->getName());
            $config->save();
        }
    }

    public function getNick(Player $player){
        $config = new Config(Loader::DIRECTORY . "Nicks.yml", Config::YAML);
        if(!$config->exists($player->getName())){
            return false;
        }else{
            return $config->get($player->getName());
        }
    }

    //Warps
    public function setWarp(Player $player, $warp){
        $config = new Config(Loader::DIRECTORY . "Warps.yml", Config::YAML);
        $pos = array();
        $pos["x"] = $player->getX();
        $pos["y"] = $player->getY();
        $pos["z"] = $player->getZ();
        $pos["yaw"] = $player->yaw;
        $pos["pitch"] = $player->pitch;
        $pos["level"] = $player->getLevel()->getName();
        $config->set($warp, $pos);
    }

    public function removeWarp($warp){
        $config = new Config(Loader::DIRECTORY . "Warps.yml", Config::YAML);
        if(!$this->warpExist($warp)){
            return false;
        }else{
            $config->remove($warp);
            return true;
        }
    }

    public function warpExist($warp){
        $config = new Config(Loader::DIRECTORY . "Warps.yml", Config::YAML);
        if(!$config->exists($warp)){
            return false;
        }else{
            return $warp;
        }
    }

    public function tpWarp(Player $player, $warp){
        $config = new Config(Loader::DIRECTORY . "Warps.yml", Config::YAML);
        if(!$config->exists($warp)){
            return false;
        }
        $home = $config->get($warp);
        if($player->getLevel()->getName() != $home["level"]){
            $player->setLevel($home["level"]);
        }
        $player->teleport(new Vector3($home["x"], $home["y"], $home["z"]), $home["yaw"], $home["pitch"]);
        return true;
    }

    public function warpList(){
        //NOTE: Consider using wordwrap($string, $width, "\n", true)
    }

    //Vanish
    public function switchVanish(Player $player){
        if($this->getSession($player, "vanish") === false){
            $this->setSession($player, "vanish", true);
            foreach(Server::getInstance()->getOnlinePlayers() as $p){
                $p->hidePlayer($player);
            }
        }else{
            $this->setSession($player, "vanish", false);
            foreach(Server::getInstance()->getOnlinePlayers() as $p){
                $p->showPlayer($player);
            }
        }
        return true;
    }

    public function isVanished(Player $player){
        if($this->getSession($player, "vanish") == false){
            return false;
        }else{
            return true;
        }
    }

    public function switchLevelVanish(Player $player, Level $origin, Level $target){
        if($this->isVanished($player)){
            foreach($origin->getPlayers() as $p){
                $p->showPlayer($player);
            }
            foreach($target->getPlayers() as $p){
                $p->hidePlayer($p);
            }
        }
    }
} 