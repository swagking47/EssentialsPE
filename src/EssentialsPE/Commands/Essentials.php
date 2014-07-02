<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class Essentials extends BaseCommand{
    public function __construct(Loader $plugin){
        parent::__construct($plugin, "essentials", "Get current Essentials version", "/essentials", ["ess"]);
        $this->setPermission("essentials.command.essentials");
    }

    public function execute(CommandSender $sender, $alias, array $args){
        if(!$this->testPermission($sender)){
            return false;
        }
        $sender->sendMessage(TextFormat::YELLOW . "You're using " . TextFormat::AQUA . "EssentialsPE " . TextFormat::GREEN . "v" . Server::getInstance()->getPluginManager()->getPlugin("EssentialsPE")->getDescription()->getVersion());
        return true;
    }
} 