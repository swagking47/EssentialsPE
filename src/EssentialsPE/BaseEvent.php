<?php
namespace EssentialsPE;

use pocketmine\event\Cancellable;
use pocketmine\event\plugin\PluginEvent;

abstract class BaseEvent extends PluginEvent implements Cancellable{
    public function __construct(Loader $plugin){
        parent::__construct($plugin);
    }
} 