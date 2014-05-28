<?php
namespace EssentialsPE\API;

use pocketmine\Player;

class Sessions {
    public $sessions = array();
    public $default = array();

    public function setDefault($key, $value){
        //$this->default[$key] = $value;
        $this->default[$key] = $value;
    }

    public function create(Player $player){
        $this->sessions[$player] = $this->default;
    }

    public function remove(Player $player){
        if(!isset($this->sessions[$player])){
            return false;
        }else{
            unset($this->sessions[$player]);
        }
    }

    public function set(Player $player, $key, $value){
        if(!isset($this->sessions[$player][$key])){
            return false;
        }else{
            $this->sessions[$player][$key] = $value;
        }
    }

    public function get(Player $player, $key){
        if(!isset($this->sessions[$player][$key])){
            return false;
        }else{
            return $this->sessions[$player][$key];
        }
    }
} 