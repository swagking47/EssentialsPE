<?php
namespace EssentialsPE;

use EssentialsPE\Commands\Broadcast; //Use API
use EssentialsPE\Commands\Burn;
use EssentialsPE\Commands\ClearInventory;
use EssentialsPE\Commands\Essentials;
use EssentialsPE\Commands\Extinguish;
use EssentialsPE\Commands\GetPos;
use EssentialsPE\Commands\God; //Use API
use EssentialsPE\Commands\Heal;
use EssentialsPE\Commands\Item as ItemCommand;
use EssentialsPE\Commands\KickAll;
use EssentialsPE\Commands\More;
use EssentialsPE\Commands\Mute; //Use API
use EssentialsPE\Commands\Nick; //Use API
use EssentialsPE\Commands\PowerTool\PowerTool;
use EssentialsPE\Commands\PowerTool\PowerToolToggle;
use EssentialsPE\Commands\PvP; //Use API
use EssentialsPE\Commands\RealName;
use EssentialsPE\Commands\Repair;
use EssentialsPE\Commands\Seen;
use EssentialsPE\Commands\SetSpawn;
use EssentialsPE\Commands\TempBan;
use EssentialsPE\Commands\Top;
use EssentialsPE\Commands\Vanish; //Use API
use EssentialsPE\Commands\Warps\RemoveWarp;
use EssentialsPE\Commands\Warps\SetWarp;
use EssentialsPE\Commands\Warps\Warp;
use EssentialsPE\Events\EventHandler; //Use API
use EssentialsPE\Events\PlayerNickChangeEvent;
use pocketmine\item\Item;
use pocketmine\level\Level;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;

class Loader extends PluginBase{
    public $path;

    public function onEnable(){
        @mkdir($this->getDataFolder());
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
        $this->getServer()->getCommandMap()->register($fallbackPrefix, new God($this));
        $this->getServer()->getCommandMap()->register($fallbackPrefix, new Heal($this));
        //$this->getServer()->getCommandMap()->register($fallbackPrefix, new ItemCommand($this)); //TODO :D
        $this->getServer()->getCommandMap()->register($fallbackPrefix, new KickAll($this));
        $this->getServer()->getCommandMap()->register($fallbackPrefix, new More($this));
        $this->getServer()->getCommandMap()->register($fallbackPrefix, new Mute($this));
        $this->getServer()->getCommandMap()->register($fallbackPrefix, new Nick($this));
        $this->getServer()->getCommandMap()->register($fallbackPrefix, new PowerTool($this));
        $this->getServer()->getCommandMap()->register($fallbackPrefix, new PowerToolToggle($this));
        $this->getServer()->getCommandMap()->register($fallbackPrefix, new PvP($this));
        $this->getServer()->getCommandMap()->register($fallbackPrefix, new RealName($this));
        $this->getServer()->getCommandMap()->register($fallbackPrefix, new Repair($this));
        $this->getServer()->getCommandMap()->register($fallbackPrefix, new Seen($this));
        $this->getServer()->getCommandMap()->register($fallbackPrefix, new SetSpawn($this));
        //$this->getServer()->getCommandMap()->register($fallbackPrefix, new TempBan($this)); //TODO :D
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

    /**
     * @param string $player
     * @return bool|Player|string
     */
    public function getPlayer($player){
        $player = strtolower($player);
        $r = "";
        foreach($this->getServer()->getOnlinePlayers() as $p){
            if(strtolower($p->getDisplayName()) === $player || strtolower($p->getName()) === $player){
                $r = $p;
            }
        }
        if($r == ""){
            return false;
        }else{
            return $r;
        }
    }

    public function colorMessage($message, $player = null){
        if($player !== null && $player instanceof Player && !$player->hasPermission("essentials.colorchat")){
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

    /**   _____              _
     *   / ____|            (_)
     *  | (___   ___ ___ ___ _  ___  _ __  ___
     *   \___ \ / _ / __/ __| |/ _ \| '_ \/ __|
     *   ____) |  __\__ \__ | | (_) | | | \__ \
     *  |_____/ \___|___|___|_|\___/|_| |_|___/
     */

    /** @var array  */
    private $sessions = [];
    /** @var array  */
    private $mutes = [];
    /** @var array  */
    private $default = [
        "god" => false,
        "powertool" => false,
        "pvp" => false,
        "vanish" => false
    ];

    /**
     * Creates a new Sessions for the specified player
     *
     * @param Player $player
     */
    public function createSession(Player $player){
        $this->sessions[$player->getName()] = $this->default;
    }

    /**
     * Removes a player's session (if active and available)
     *
     * @param Player $player
     */
    public function removeSession(Player $player){
        unset($this->sessions[$player->getName()]);
    }

    /**
     * Modify the value of a session key (See "Mute" for example)
     *
     * @param Player $player
     * @param $key
     * @param $value
     * @return bool
     */
    public function setSession(Player $player, $key, $value){
        if(!(isset($this->sessions[$player->getName()]) || isset($this->sessions[$player->getName()][$key]))){
            return false;
        }
        $this->sessions[$player->getName()][$key] = $value;
        return true;
    }

    /**
     * Return the value of a session key
     *
     * @param Player $player
     * @param $key
     * @return bool
     */
    public function getSession(Player $player, $key){
        if(!(isset($this->sessions[$player->getName()]) || isset($this->sessions[$player->getName()][$key]))){
            return false;
        }
        return $this->sessions[$player->getName()][$key];
    }

    /**   _____           _
     *   / ____|         | |
     *  | |  __  ___   __| |
     *  | | |_ |/ _ \ / _` |
     *  | |__| | (_) | (_| |
     *   \_____|\___/ \__,_|
     */

    /**
     * Set the God Mode on or off
     *
     * @param Player $player
     * @param $state
     * @return bool
     */
    public function setGodMode(Player $player, $state){
        if(!is_bool($state)){
            return false;
        }
        $this->setSession($player, "god", $state);
        return true;
    }

    /**
     * Switch God Mode on/off automatically
     *
     * @param Player $player
     */
    public function switchGodMode(Player $player){
        if(!$this->isGod($player)){
            $this->setGodMode($player, true);
        }else{
            $this->setGodMode($player, false);
        }
    }

    /**
     * Tell if a player is in God Mode
     *
     * @param Player $player
     * @return bool
     */
    public function isGod(Player $player){
        if($this->getSession($player, "god") == false){
            return false;
        }else{
            return true;
        }
    }

    /**  _    _
     *  | |  | |
     *  | |__| | ___  _ __ ___   ___
     *  |  __  |/ _ \| '_ ` _ \ / _ \
     *  | |  | | (_) | | | | | |  __/
     *  |_|  |_|\___/|_| |_| |_|\___|
     */

    /**
     * Sets a new home location or modify it if the home exists
     *
     * @param Player $player
     * @param $home_name
     * @return bool
     */
    public function setHome(Player $player, $home_name){
        $config = new Config($this->getDataFolder() . $player->getName() . ".yml");
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

    /**
     * Teleport to the selected home
     *
     * @param Player $player
     * @param $home_name
     * @return bool
     */
    public function homeTp(Player $player, $home_name){
        $config = new Config($this->getDataFolder() . $player->getName() . ".yml");
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

    /**
     * Count the number of homes that a player has
     *
     * @param Player $player
     * @return int
     */
    public function countHomes(Player $player){
        $config = new Config($this->getDataFolder() . $player->getName() . ".yml");
        return count($config->getAll());
    }

    /**  __  __       _
     *  |  \/  |     | |
     *  | \  / |_   _| |_ ___
     *  | |\/| | | | | __/ _ \
     *  | |  | | |_| | ||  __/
     *  |_|  |_|\__,_|\__\___|
     */

    /**
     * Create the mute session for a player
     *
     * @param Player $player
     */
    public function muteSessionCreate(Player $player){
        if(!isset($this->mutes[$player->getName()])){
            $this->mutes[$player->getName()] = false;
        }
    }

    /**
     * Set the Mute mode on or off
     *
     * @param Player $player
     * @param $state
     * @return bool
     */
    public function setMute(Player $player, $state){
        if(!is_bool($state)){
            return false;
        }
        $this->mutes[$player->getName()] = $state;
        return true;
    }

    /**
     * Switch the Mute mode on/off automatically
     *
     * @param Player $player
     */
    public function switchMute(Player $player){
        if(!$this->isMuted($player)){
            $this->setMute($player, true);
        }else{
            $this->setMute($player, false);
        }
    }

    /**
     * Tell if the is Muted or not
     *
     * @param Player $player
     * @return bool
     */
    public function isMuted(Player $player){
        if($this->mutes[$player->getName()] == false){
            return false;
        }else{
            return true;
        }
    }

    /** _   _ _      _
     * | \ | (_)    | |
     * |  \| |_  ___| | __
     * | . ` | |/ __| |/ /
     * | |\  | | (__|   <
     * |_| \_|_|\___|_|\_\
     */

    /**
     * Change the player name for chat and even on his NameTag (aka Nick)
     *
     * @param Player $player
     * @param $nick
     * @param bool $save
     */
    public function setNick(Player $player, $nick, $save = true){
        $config = new Config($this->getDataFolder() . "Nicks.yml", Config::YAML);
        $this->getServer()->getPluginManager()->callEvent($event = new PlayerNickChangeEvent($this, $player, $nick));
        if($event->isCancelled()){
            return;
        }
        $nick = $event->getNewNick();
        $player->setNameTag($nick);
        $player->setDisplayName($nick);
        $player->sendMessage(TextFormat::YELLOW . "Your nick is now $nick");
        if($save == true){
            $config->set($player->getName(), $nick);
            $config->save();
        }
    }

    /**
     * Restore the original player name for chat and on his NameTag
     *
     * @param Player $player
     * @param bool $save
     */
    public function removeNick(Player $player, $save = true){
        $config = new Config($this->getDataFolder() . "Nicks.yml", Config::YAML);
        $this->getServer()->getPluginManager()->callEvent($event = new PlayerNickChangeEvent($this, $player, $player->getName()));
        if($event->isCancelled()){
            return;
        }
        $player->setNameTag($player->getName());
        $player->setDisplayName($player->getName());
        $player->sendMessage(TextFormat::YELLOW . "Your nick has been disabled");
        if($save === true){
            $config->remove($player->getName());
            $config->save();
        }
    }

    /**
     * Get's the player current Nick
     *
     * @param Player $player
     * @return bool|mixed
     */
    public function getNick(Player $player){
        $config = new Config($this->getDataFolder() . "Nicks.yml", Config::YAML);
        if(!$config->exists($player->getName())){
            return false;
        }else{
            return $config->get($player->getName());
        }
    }

    /**  _____                    _______          _
     *  |  __ \                  |__   __|        | |
     *  | |__) _____      _____ _ __| | ___   ___ | |
     *  |  ___/ _ \ \ /\ / / _ | '__| |/ _ \ / _ \| |
     *  | |  | (_) \ V  V |  __| |  | | (_) | (_) | |
     *  |_|   \___/ \_/\_/ \___|_|  |_|\___/ \___/|_|
     */

    /**
     * Tell is PowerTool is enabled for a player, doesn't matter on what item
     *
     * @param Player $player
     * @return bool
     */
    public function isPowerToolEnabled(Player $player){
        if($this->getSession($player, "powertool") === false){
            return false;
        }else{
            return true;
        }
    }

    /**
     * Sets a command for the item you have in hand
     * NOTE: If the hand is empty, it will be cancelled
     *
     * @param Player $player
     * @param Item $item
     * @param $command_line
     */
    public function setPowerToolItemCommand(Player $player, Item $item, $command_line){
        if($item == Item::AIR){
            return;
        }
        $this->sessions[$player->getName()]["powertool"][$item->getID()] = $command_line;
    }

    /**
     * Return the command attached to the specified item if it's available
     *
     * @param Player $player
     * @param Item $item
     * @return bool
     */
    public function getPowerToolItemCommand(Player $player, Item $item){
        if(!isset($this->sessions[$player->getName()]["powertool"][$item->getID()])){
            return false;
        }
        return $this->sessions[$player->getName()]["powertool"][$item->getID()];
    }

    /**
     * Remove the command only for the item in hand
     *
     * @param Player $player
     * @param Item $item
     */
    public function disablePowerToolItem(Player $player, Item $item){
        unset($this->sessions[$player->getName()]["powertool"][$item->getID()]);
    }

    /**
     * Remove the commands for all the items of a player
     *
     * @param Player $player
     */
    public function disablePowerTool(Player $player){
        $this->setSession($player, "powertool", false);
    }

    /**  _____        _____
     *  |  __ \      |  __ \
     *  | |__) __   _| |__) |
     *  |  ___/\ \ / |  ___/
     *  | |     \ V /| |
     *  |_|      \_/ |_|
     */

    /**
     * Set the PvP mode on or off
     *
     * @param Player $player
     * @param $state
     * @return bool
     */
    public function setPvP(Player $player, $state){
        if(!is_bool($state)){
            return false;
        }
        $this->setSession($player, "pvp", $state);
        return true;
    }

    /**
     * Switch the PvP mode on/off automatically
     *
     * @param Player $player
     */
    public function switchPvP(Player $player){
        if(!$this->isPvPEnabled($player)){
            $this->setPvP($player, true);
        }else{
            $this->setPvP($player, false);
        }
    }

    /**
     * Tell if the PvP mode is enabled for the specified player, or not
     *
     * @param Player $player
     * @return bool
     */
    public function isPvPEnabled(Player $player){
        if($this->getSession($player, "pvp") === false){
            return false;
        }else{
            return true;
        }
    }

    /** __          __
     *  \ \        / /
     *   \ \  /\  / __ _ _ __ _ __
     *    \ \/  \/ / _` | '__| '_ \
     *     \  /\  | (_| | |  | |_) |
     *      \/  \/ \__,_|_|  | .__/
     *                       | |
     *                       |_|
     */

    /**
     * Set's a new Warp or modify the position if already exists
     * it use Player to handle the position, but may change later
     *
     * @param Player $player
     * @param $warp
     */
    public function setWarp(Player $player, $warp){
        $config = new Config($this->getDataFolder() . "Warps.yml", Config::YAML);
        $pos = array();
        $pos["x"] = $player->getX();
        $pos["y"] = $player->getY();
        $pos["z"] = $player->getZ();
        $pos["yaw"] = $player->yaw;
        $pos["pitch"] = $player->pitch;
        $pos["level"] = $player->getLevel()->getName();
        $config->set($warp, $pos);
    }

    /**
     * Remove a Warp if exists
     *
     * @param $warp
     * @return bool
     */
    public function removeWarp($warp){
        $config = new Config($this->getDataFolder() . "Warps.yml", Config::YAML);
        if(!$this->warpExist($warp)){
            return false;
        }else{
            $config->remove($warp);
            return true;
        }
    }

    /**
     * Tell if a Warp exists
     *
     * @param $warp
     * @return bool
     */
    public function warpExist($warp){
        $config = new Config($this->getDataFolder() . "Warps.yml", Config::YAML);
        if(!$config->exists($warp)){
            return false;
        }else{
            return true;
        }
    }

    /**
     * Teleport a player to a Warp
     *
     * @param Player $player
     * @param $warp
     * @return bool
     */
    public function tpWarp(Player $player, $warp){
        $config = new Config($this->getDataFolder() . "Warps.yml", Config::YAML);
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

    /**
     * Return a list with all the available warps
     *
     * TODO
     */
    public function warpList(){
        //NOTE: Consider using wordwrap($string, $width, "\n", true)
    }

    /** __      __         _     _
     *  \ \    / /        (_)   | |
     *   \ \  / __ _ _ __  _ ___| |__
     *    \ \/ / _` | '_ \| / __| '_ \
     *     \  | (_| | | | | \__ | | | |
     *      \/ \__,_|_| |_|_|___|_| |_|
     */

    /**
     * Set the Vanish mode on or off
     *
     * @param Player $player
     * @param $state
     * @return bool
     */
    public function setVanish(Player $player, $state){
        if(!is_bool($state)){
            return false;
        }
        $this->setSession($player, "vanish", $state);
        if($state === false){
            foreach($this->getServer()->getOnlinePlayers() as $p){
                $p->showPlayer($player);
            }
        }else{
            foreach($this->getServer()->getOnlinePlayers() as $p){
                $p->hidePlayer($player);
            }
        }
        return true;
    }

    /**
     * Switch the Vanish mode on/off automatically
     *
     * @param Player $player
     * @return bool
     */
    public function switchVanish(Player $player){
        if(!$this->isVanished($player)){
            $this->setVanish($player, true);

        }else{
            $this->setVanish($player, false);
            foreach($this->getServer()->getOnlinePlayers() as $p){
                $p->showPlayer($player);
            }
        }
        return true;
    }

    /**
     * Tell if a player is Vanished, or not
     *
     * @param Player $player
     * @return bool
     */
    public function isVanished(Player $player){
        if($this->getSession($player, "vanish") == false){
            return false;
        }else{
            return true;
        }
    }

    /**
     * Allow to switch between levels Vanished!
     * you need to teleport the player first and call the EntityLevelChangeEvent first...
     *
     * @param Player $player
     * @param Level $origin
     * @param Level $target
     */
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