<?php
namespace EssentialsPE\Events;


use EssentialsPE\BaseEvent;
use EssentialsPE\Loader;
use pocketmine\Player;

class PlayerNickChangeEvent extends BaseEvent{
    /** @var \pocketmine\Player  */
    protected  $player;
    /** @var  string */
    protected  $new_nick;
    /** @var  string */
    protected  $old_nick;

    /**
     * @param Loader $plugin
     * @param Player $player
     * @param $new_nick
     * @param $old_nick
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
    public function getNewNick(){
        return $this->new_nick;
    }
    public function getOldNick(){
        return $this->old_nick;
    }

    /**
     * Changes the nick to be set.
     *
     * @param $nick
     */
    public function setNick($nick){
        $this->new_nick = $nick;
    }
} 