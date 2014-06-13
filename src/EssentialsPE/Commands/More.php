<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use EssentialsPE\Loader;

class More extends BaseCommand{
    public function __construct(Loader $plugin) {
        parent::__construct("more", "Get more of the item you hold", "/more");
        $this->setPermission("essentials.more");
        $this->plugin = $plugin;
    }
    
    public function execute(CommandSender $sender, $alias, array $args) {
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
    }
}
