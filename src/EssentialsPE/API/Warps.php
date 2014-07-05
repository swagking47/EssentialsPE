<?php
namespace EssentialsPE\API;

use EssentialsPE\Loader;
use pocketmine\block\Block;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\utils\Config;

class Warps {
    public $config;
    private $player;

    public function __construct(Player $player){
        $this->config = new Config(Loader::DIRECTORY . "Warps.yml", Config::YAML);
        $this->player = $player; //Just to handle the position of the warp and teleport
    }

    public function set($name){
        $pos = array();
        $pos["x"] = $this->player->getX();
        $pos["y"] = $this->player->getY();
        $pos["z"] = $this->player->getZ();
        $pos["yaw"] = $this->player->yaw;
        $pos["pitch"] = $this->player->pitch;
        $pos["level"] = $this->player->getLevel()->getName();
        $this->config->set($name, $pos);
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

    public function warpsList(){
        //NOTE: Consider using wordwrap($string, $width, "\n", true)
    }
} 