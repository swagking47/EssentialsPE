<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class More extends BaseCommand{
    public function __construct(Loader $plugin){
        parent::__construct($plugin, "more", "Get a stack of the item you're holding", "/more");
        $this->setPermission("essentials.command.more");
    }

    public function execute(CommandSender $sender, $alias, array $args){
        if(!$this->testPermission($sender)){
            return false;
        }
        if(!$sender instanceof Player){
            $sender->sendMessage(TextFormat::RED . "Please run this command in-game.");
        }else{
            $inv = $sender->getInventory();
            $item = $inv->getItemInHand();
            $item->setCount($item->getMaxStackSize());
            $inv->setItemInHand($item);
        }
        return true;
    }
} 