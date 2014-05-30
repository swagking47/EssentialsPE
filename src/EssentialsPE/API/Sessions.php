<?php
namespace EssentialsPE\API;

use pocketmine\Player;

class Sessions {
    /** @var Sessions */
    private static $instance;

    /** @var array */
    public $sessions = array();
    /** @var array */
    public $default = array();

    public function __construct(){
        self::$instance = $this;
    }

    /**
     * @return Sessions
     */
    public static function getInstance(){
        return self::$instance;
    }

    /**
     * @param $key
     * @param $value
     */
    public function setDefault($key, $value){
        $this->default[$key] = $value;
    }

    /**
     * @param Player $player
     */
    public function create(Player $player){
        $this->sessions[$player->getName()] = $this->default;
    }

    /**
     * @param Player $player
     * @return bool
     */
    public function remove(Player $player){
        if(!isset($this->sessions["$player->getName()"])){
            return false;
        }else{
            unset($this->sessions["$player->getName()"]);
        }
    }

    /**
     * @param Player $player
     * @param $key
     * @param $value
     * @return bool
     */
    public function set(Player $player, $key, $value){
        if(!isset($this->sessions["$player->getName()"][$key])){
            return false;
        }else{
            $this->sessions["$player->getName()"][$key] = $value;
        }
    }

    /**
     * @param Player $player
     * @param $key
     * @return mixed
     */
    public function get(Player $player, $key){
        if(!isset($this->sessions["$player->getName()"][$key])){
            return false;
        }else{
            return $this->sessions["$player->getName()"][$key];
        }
    }
} 