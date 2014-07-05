<?php
namespace EssentialsPE\API;

use EssentialsPE\Loader;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;

class Homes {
    public $config;
    public $player;

    public function __construct(Player $player){
        $this->config = new Config(Loader::DIRECTORY . $player->getName() . ".yml");
        $this->player = $player;
    }

    public function set($name){
        if(!$this->config->exists($name)){
            if(!$this->player->hasPermission("essentials.home." . ($this->count() + 1))){
                return false;
            }
            $pos = array();
            $pos["x"] = $this->player->getX();
            $pos["y"] = $this->player->getY();
            $pos["z"] = $this->player->getZ();
            $pos["yaw"] = $this->player->yaw;
            $pos["pitch"] = $this->player->pitch;
            $pos["level"] = $this->player->getLevel()->getName();
            $this->config->set($name, $pos);
        }
        return true;
    }

    public function tp($name){
        if(!$this->config->exists($name)){
            return false;
        }
        $home = $this->config->get($name);
        if($this->player->getLevel()->getName() != $home["level"]){
            $this->player->setLevel($home["level"]);
        }
        $this->player->teleport(new Vector3($home["x"], $home["y"], $home["z"]), $home["yaw"], $home["pitch"]);
        return true;
    }

    private function count(){
        return count($this->config->getAll());
    }
}
