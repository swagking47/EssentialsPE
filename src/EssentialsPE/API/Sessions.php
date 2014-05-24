<?php
/**
 * NOTE: I'm not sure if this works... if it doesn't, then we will need to set sessions in each command class :/
 */
namespace EssentialsPE\API;

use pocketmine\Player;

class Sessions{
    public $sessions = array();
    public $defaults = array();
    
    public function setDefault($key, $value){
        $this->defaults[$key] = $value;
    }
    
    public function getDefault($key){
        if(isset($this->defaults[$key])){
            return($this->defaults[$key]);
        }else{
            return false;
        }
    }
    
    
    //  Here comes the extensible API:
    public function set(Player $player, $key, $value){
        $p = $player->getName();
        if(!isset($this->sessions[$p])){
            return false;
        }else{
            $this->sessions[$p][$key] = $value;
        }
    }
    
    public function get(Player $player, $key){
        $p = $player->getName();
        if(!isset($this->sessions[$p]) or isset($this->sessions[$p][$key])){
            return false;
        }else{
            return($this->sessions[$p][$key]);
        }
    }
    
    
    
    //  DO NOT CALL THE NEXT FUNCTIONS, THEY CAN BE DANGEROUS:
    public function create(Player $player){
        $p = $player->getName();
        if(isset($this->sessions[$p])){
            $this->destroy($p);
        }else{
            $this->sessions[$p] = $this->defaults;
        }
    }
    
    public function destroy(Player $player){
        $p = $player->getName();
        if(!isset($this->sessions[$p])){
            return false;
        }else{
            unset($this->sessions[$p]);
        }
    }
}
