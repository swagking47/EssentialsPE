<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\item\Item;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use EssentialsPE\Loader;

class Repair extends BaseCommand{
    public function __construct(Loader $plugin) {
        parent::__construct("repair", "Repair the item you're holding", "/repair", ["fix"]);
        $this->setPermission("essentials.repair");
        $this->plugin = $plugin;
    }
    
    public function execute(CommandSender $sender, $alias, array $args) {
        if(!$this->testPermission($sender)){
        }
        if(!$sender instanceof Player){
            $sender->sendMessage(TextFormat::RED . "Please run this command in-game");
        }else{
            $inv = $sender->getInventory();
            $item = $inv->getItemInHand();
            $item->setDamage(0);
            $inv->setItemInHand($item);
        }
    }
}
