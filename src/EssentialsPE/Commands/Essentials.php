<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use EssentialsPE\Loader;

class Essentials extends BaseCommand{
    public function __construct(Loader $plugin) {
        parent::__construct("essentials", "Get the current Essentials version", "/essentials", ["ess"]);
        $this->setPermission("essentials.essentials");
        $this->plugin = $plugin;
    }
    
    public function execute(CommandSender $sender, $alias, array $args) {
        if(!$this->testPermission($sender)){
        }
        $sender->sendMessage(TextFormat::YELLOW . "You're using " . TextFormat::AQUA . "EssentialsPE v" . Server::getInstance()->getPluginManager()->getPlugin("EssentialsPE")->getDescription()->getVersion());
        return true;
    }
}
