<?php
namespace EssentialsPE;

use pocketmine\command\Command;
use pocketmine\command\PluginIdentifiableCommand;

abstract class BaseCommand extends Command implements PluginIdentifiableCommand{
    /** @var \pocketmine\plugin\Plugin */
    public $plugin;
    
    public function getPlugin() {
        return $this->plugin;
    }
}
