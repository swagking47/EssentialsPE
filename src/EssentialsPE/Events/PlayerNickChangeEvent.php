<?php
namespace EssentialsPE\Events;


use EssentialsPE\BaseEvent;
use EssentialsPE\Loader;
use pocketmine\event\Cancellable;
use pocketmine\Player;

class PlayerNickChangeEvent extends BaseEvent implements Cancellable{
    public static $handlerList = null;

    /** @var Player  */
    private $player;
    /** @var  string */
    private   $new_nick;
    /** @var  string */
    private   $old_nick;

    /**
     * @param Loader $plugin
     * @param Player $player
     * @param $new_nick
     */
    public function __construct(Loader $plugin, Player $player, $new_nick){
        parent::__construct($plugin);
        $this->player = $player;
        $this->new_nick = $new_nick;
        $this->old_nick = $player->getDisplayName();
    }

    /**
     * @return Player
     */
    public function getPlayer(){
        return $this->player;
    }

    /**
     * @return string
     */
    public function getNewNick(){
        return $this->new_nick;
    }

    /**
     * @return string
     */
    public function getOldNick(){
        return $this->old_nick;
    }

    /**
     * @param string $nick
     */
    public function setNick($nick){
        $this->new_nick = $nick;
    }
} 