<?php
namespace EssentialsPE;

use EssentialsPE\Commands\Broadcast; //Use API
use EssentialsPE\Commands\Burn;
use EssentialsPE\Commands\ClearInventory;
use EssentialsPE\Commands\DefaultCommands\Me; //Use API
use EssentialsPE\Commands\Essentials;
use EssentialsPE\Commands\Extinguish;
use EssentialsPE\Commands\GetPos;
use EssentialsPE\Commands\God; //Use API
use EssentialsPE\Commands\Heal;
use EssentialsPE\Commands\KickAll;
use EssentialsPE\Commands\More;
use EssentialsPE\Commands\Mute; //Use API
use EssentialsPE\Commands\Nick; //Use API
use EssentialsPE\Commands\PvP; //Use API
use EssentialsPE\Commands\RealName;
use EssentialsPE\Commands\Repair;
use EssentialsPE\Commands\Seen;
use EssentialsPE\Commands\SetSpawn;
use EssentialsPE\Commands\SignRegister; //Use API
use EssentialsPE\Commands\Top;
use EssentialsPE\Commands\Vanish; //Use API
use EssentialsPE\Commands\Warps\RemoveWarp;
use EssentialsPE\Commands\Warps\SetWarp;
use EssentialsPE\Commands\Warps\Warp;
use EssentialsPE\Events\EventHandler; //Use API
use EssentialsPE\Events\PlayerNickChangeEvent;
use pocketmine\level\Level;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use pocketmine\event\Listener;

class Loader extends PluginBase implements Listener{
    const DIRECTORY = "plugins/EssentialsPE/";

    public function onEnable(){
        @mkdir(Loader::DIRECTORY);
	    $this->getLogger()->info(TextFormat::YELLOW . "Loading...");
        $this->getServer()->getPluginManager()->registerEvents(new EventHandler($this), $this);
        $this->registerCommands();

        foreach($this->getServer()->getOnlinePlayers() as $p){
            //Nicks
            $this->setNick($p, $this->getNick($p), false);
            //Sessions & Mute
            $this->muteSessionCreate($p);
            $this->createSession($p);
        }
    }

    public function onDisable(){
        foreach($this->getServer()->getOnlinePlayers() as $p){
            //Nicks
            $this->setNick($p, $p->getName(), false);
            //Vanish
            if($this->getSession($p, "vanish") === true){
                foreach($this->getServer()->getOnlinePlayers() as $players){
                    $players->showPlayer($p);
                }
            }
        }
    }

    private function registerCommands(){
        $fallbackPrefix = "essentialspe";
        $this->getServer()->getCommandMap()->register($fallbackPrefix, new Broadcast($this));
        $this->getServer()->getCommandMap()->register($fallbackPrefix, new Burn($this));
        $this->getServer()->getCommandMap()->register($fallbackPrefix, new ClearInventory($this));
        $this->getServer()->getCommandMap()->register($fallbackPrefix, new Essentials($this));
        $this->getServer()->getCommandMap()->register($fallbackPrefix, new Extinguish($this));
        $this->getServer()->getCommandMap()->register($fallbackPrefix, new GetPos($this));
        $this->getServer()->getCommandMap()->register($fallbackPrefix, new God($this)); //Experimental
        $this->getServer()->getCommandMap()->register($fallbackPrefix, new Heal($this));
        $this->getServer()->getCommandMap()->register($fallbackPrefix, new KickAll($this));
        $this->getServer()->getCommandMap()->register($fallbackPrefix, new More($this));
        $this->getServer()->getCommandMap()->register($fallbackPrefix, new Mute($this));
        $this->getServer()->getCommandMap()->register($fallbackPrefix, new Nick($this));
        $this->getServer()->getCommandMap()->register($fallbackPrefix, new PvP($this)); //Experimental
        $this->getServer()->getCommandMap()->register($fallbackPrefix, new RealName($this));
        $this->getServer()->getCommandMap()->register($fallbackPrefix, new Repair($this));
        $this->getServer()->getCommandMap()->register($fallbackPrefix, new Seen($this));
        $this->getServer()->getCommandMap()->register($fallbackPrefix, new SetSpawn($this));
        //$this->getServer()->getCommandMap()->register($fallbackPrefix, new SignRegister($this)); //TODO
        $this->getServer()->getCommandMap()->register($fallbackPrefix, new Top($this));
        $this->getServer()->getCommandMap()->register($fallbackPrefix, new Vanish($this));

        //Warps:
        //$this->getServer()->getCommandMap()->register($fallbackPrefix, new RemoveWarp($this)); //TODO
        //$this->getServer()->getCommandMap()->register($fallbackPrefix, new SetWarp($this)); //TODO
        //$this->getServer()->getCommandMap()->register($fallbackPrefix, new Warp($this)); //TODO

        //Default Commands:
        //$this->getServer()->getCommandMap()->register($fallbackPrefix, new Me($this)); //TODO
    }

    /*
     *  .----------------.  .----------------.  .----------------.
     * | .--------------. || .--------------. || .--------------. |
     * | |      __      | || |   ______     | || |     _____    | |
     * | |     /  \     | || |  |_   __ \   | || |    |_   _|   | |
     * | |    / /\ \    | || |    | |__) |  | || |      | |     | |
     * | |   / ____ \   | || |    |  ___/   | || |      | |     | |
     * | | _/ /    \ \_ | || |   _| |_      | || |     _| |_    | |
     * | ||____|  |____|| || |  |_____|     | || |    |_____|   | |
     * | |              | || |              | || |              | |
     * | '--------------' || '--------------' || '--------------' |
     *  '----------------'  '----------------'  '----------------'
     *
     */

    public function colorMessage($message, $player = false){
        if($player !== false && $player instanceof Player && !$player->hasPermission("essentials.colorchat")){
            return $message;
        }
        $search = ["&0", "&1", "&2", "&3", "&4", "&5", "&6", "&7", "&8", "&9", "&a", "&b", "&c", "&d", "&e", "&f", "&k", "&l", "&m", "&n", "&o", "&r"];
        //$formats = ["§0", "§1", "§2", "§3", "§4", "§5", "§6", "§7", "§8", "§9", "§a", "§b", "§c", "§d", "§e", "§f", "§k", "§l", "§m", "§n", "§o", "§r"];
        foreach($search as $s){
            $code = substr($s, -1, 1);
            $message = str_replace($s, "§" . $code, $message);
        }
        return $message;
    }
    //Sessions
    private $sessions = [];
    private $mutes = [];
    private $default = [
        "god" => false,
        "pvp" => false,
        "signregister" => false,
        "vanish" => false
    ];

    public function createSession(Player $player){
        $this->sessions[$player->getName()] = $this->default;
    }

    public function removeSession(Player $player){
        unset($this->sessions[$player->getName()]);
    }

    public function setSession(Player $player, $key, $value){
        if(!(isset($this->sessions[$player->getName()]) || isset($this->sessions[$player->getName()][$key]))){
            return false;
        }
        $this->sessions[$player->getName()][$key] = $value;
        return true;
    }

    public function getSession(Player $player, $key){
        if(!(isset($this->sessions[$player->getName()]) || isset($this->sessions[$player->getName()][$key]))){
            return false;
        }
        return $this->sessions[$player->getName()][$key];
    }

    //God
    public function switchGodMode(Player $player){
        if(!$this->isGod($player)){
            $this->setSession($player, "god", true);
        }else{
            $this->setSession($player, "god", false);
        }
    }

    public function isGod(Player $player){
        if($this->getSession($player, "god") == false){
            return false;
        }else{
            return true;
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
    public function muteSessionCreate(Player $player){
        if(!isset($this->mutes[$player->getName()])){
            $this->mutes[$player->getName()] = false;
        }
    }

    public function switchMute(Player $player){
        if(!$this->isMuted($player)){
            $this->mutes[$player->getName()] = true;
        }else{
            $this->mutes[$player->getName()] = false;
        }
    }

    public function isMuted(Player $player){
        if($this->mutes[$player->getName()] == false){
            return false;
        }else{
            return true;
        }
    }

    //Nick
    public function setNick(Player $player, $nick, $save = true){
        $config = new Config(Loader::DIRECTORY . "Nicks.yml", Config::YAML);
        //$this->getServer()->getPluginManager()->callEvent(new PlayerNickChangeEvent($this, $player, $nick, $player->getDisplayName()));
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
        //$this->getServer()->getPluginManager()->callEvent(new PlayerNickChangeEvent($this, $player, $nick, $player->getDisplayName()));
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

    //Player vs Player (aka PvP)
    public function switchPvP(Player $player){
        if(!$this->isPvPEnabled($player)){
            $this->setSession($player, "pvp", true);
        }else{
            $this->setSession($player, "pvp", false);
        }
    }

    public function isPvPEnabled(Player $player){
        if($this->getSession($player, "pvp") === false){
            return false;
        }else{
            return true;
        }
    }

    //Sign Register
    public function getSignRegisterState(Player $player){
        if($this->sessions[$player->getName()]["signregister"]["warp"] === false || $this->sessions[$player->getName()]["signregister"]["teleport"] === false){
            return false;
        }else{
            if($this->sessions[$player->getName()]["signregister"]["warp"] !== false){
                return "warp";
            }elseif($this->sessions[$player->getName()]["signregister"]["teleport"] !== false){
                return "teleport";
            }
        }
    }

    public function enableTPSignRegistration(Player $player, Vector3 $coords){
        $this->sessions[$player->getName()]["signregister"]["x"] = $coords->getFloorX();
        $this->sessions[$player->getName()]["signregister"]["y"] = $coords->getFloorX();
        $this->sessions[$player->getName()]["signregister"]["z"] = $coords->getFloorZ();
    }

    public function getTPSignRegister(Player $player){
        if($this->sessions[$player->getName()]["signregister"]["teleport"] === false){
            return false;
        }
        return new Vector3($this->sessions[$player->getName()]["signregister"]["teleport"]["x"], $this->sessions[$player->getName()]["signregister"]["teleport"]["y"], $this->sessions[$player->getName()]["signregister"]["teleport"]["z"]);
    }

    public function disableTPSignRegistration(Player $player){
        $this->sessions[$player->getName()]["signregister"]["teleport"] = false;
    }

    public function enableWarpSignRegistration(Player $player, $warp_name){
        if(!$this->warpExist($warp_name)){
            return false;
        }
        $this->sessions[$player->getName()]["signregister"]["warp"] = $warp_name;
        return true;
    }

    public function getWarpSignRegister(Player $player){
        if($this->sessions[$player->getName()]["signregister"]["warp"] === false){
            return false;
        }
        return $this->sessions[$player->getName()]["signregister"]["warp"];
    }

    public function disableWarpSignRegistration(Player $player){
        $this->sessions[$player->getName()]["signregister"]["warp"] = false;
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
            return true;
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
        if(!$this->isVanished($player)){
            $this->setSession($player, "vanish", true);
            foreach($this->getServer()->getOnlinePlayers() as $p){
                $p->hidePlayer($player);
            }
        }else{
            $this->setSession($player, "vanish", false);
            foreach($this->getServer()->getOnlinePlayers() as $p){
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