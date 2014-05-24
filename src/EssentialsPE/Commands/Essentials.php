<?php

namespace EssentialsPE\Commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class Essentials extends Command{
    public function __construct() {
        parent::__construct("essentials", "Get the current Essentials version", "/essentials", ["ess"]);
        $this->setPermission("essentials.version");
    }
    
    public function execute(CommandSender $sender, $alias, array $args) {
        if(!$this->testPermission($sender)){
            return false;
        }
        $sender->sendMessage(TextFormat::YELLOW . "You're using " . TextFormat::GREEN . "EssentialsPE v" . Server::getInstance()->getPluginManager()->getPlugin("EssentialsPE")->getDescription()->getVersion());
        return true;
    }
}
