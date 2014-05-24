<?php
namespace EssentialsPE\Commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\item\Item;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class Repair extends Command{
    public function __construct() {
        parent::__construct("repair", "Repair the iten you're holding", "/repair", ["fix"]);
        $this->setPermission("essentials.repair");
    }
    
    public function execute(CommandSender $sender, $alias, array $args) {
        if(!$this->testPermission($sender)){
            return false;
        }
        if(!$sender instanceof Player){
            $sender->sendMessage(TextFormat::RED . "Please run this command in-game");
        }else{
            $item = $sender->getSlot($sender->getCurrentEquipmentSlot());
            //Code to modify item's damage (TO-DO)
        }
    }
}
