<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Repair extends BaseCommand{
    public function __construct(Loader $plugin){
        parent::__construct($plugin, "repair", "Repair the item you're holding", "/repair", ["fix"]);
        $this->setPermission("essentials.command.repair");
    }

    public function execute(CommandSender $sender, $alias,  array $args){
        if(!$this->testPermission($sender)){
            return false;
        }
        if(!$sender instanceof Player){
            $sender->sendMessage(TextFormat::RED . "Please run this command in-game.");
        }else{
            $inv = $sender->getInventory();
            $item = $inv->getItemInHand();
            $item->setDamage(0);
            $inv->setItemInHand($item);
        }
        return true;
    }
} 