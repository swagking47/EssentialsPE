<?php
namespace EssentialsPE\API;

use EssentialsPE\Loader;
use pocketmine\Player;
use pocketmine\utils\Config;

class Nicks {
    public $config;
    public $player;

    public function __construct(Player $player){
        $this->config = new Config(Loader::DIRECTORY . "Nicks.yml", Config::YAML);
        $this->player = $player;
    }

    public function set($nick, $save = true){
        if($nick !== false){
            $this->player->setNameTag($nick);
            $this->player->setDisplayName($nick);
            if($save == true){
                $this->config->set($this->player->getName(), $nick);
                $this->config->save();
            }
        }
        return true;
    }

    public function get(){
        if(!$this->config->exists($this->player->getName())){
            return false;
        }else{
            return $this->config->get($this->player->getName());
        }
    }
} 