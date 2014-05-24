<?php
namespace EssentialsPE\Commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class More extends Command{
    public function __construct() {
        parent::__construct("more", "Get more of the item you hold", "/more");
        $this->setPermission("essentials.more");
    }
    
    public function execute(CommandSender $sender, $alias, array $args) {
        if(!$this->testPermission($sender)){
            return false;
        }
        if(!($sender instanceof Player)){
            $sender->sendMessage(TextFormat::RED . "Please run this command in-game.");
        }else{
            $item = Item::get($sender->getCurrentEquipmentSlot());
            $item->setCount(64);
            $sender->setSlot($sender->getCurrentEquipmentSlot(), $item);
        }
    }
}
