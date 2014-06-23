<?php
namespace EssentialsPE;

use pocketmine\command\Command;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\Player;
use pocketmine\Server;

abstract class BaseCommand extends Command implements PluginIdentifiableCommand{
    /** @var \pocketmine\plugin\Plugin */
    public $plugin;

    public function __construct($name, $description = "", $usageMessage = null, Loader $plugin , array $aliases = []){
        parent::__construct($name, $description, $usageMessage, $aliases);
        $this->plugin = $plugin;
    }

    public function getPlugin() {
        return $this->plugin;
    }
}
