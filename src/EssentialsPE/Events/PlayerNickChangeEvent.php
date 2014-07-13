<?php
namespace EssentialsPE\Events;


use EssentialsPE\BaseEvent;
use EssentialsPE\Loader;
use pocketmine\Player;

class PlayerNickChangeEvent extends BaseEvent{
    /** @var Player  */
    protected  $player;
    /** @var  string */
    protected  $new_nick;
    /** @var  string */
    protected  $old_nick;

    public function __construct(Loader $plugin, Player $player, $new_nick){
        parent::__construct($plugin);
        $this->player = $player;
        $this->new_nick = $new_nick;
        $this->old_nick = $player->getDisplayName();
    }

    public function getPlayer(){
        return $this->player;
    }

    public function getNewNick(){
        return $this->new_nick;
    }

    public function getOldNick(){
        return $this->old_nick;
    }

    public function setNick($nick){
        $this->new_nick = $nick;
    }
} 