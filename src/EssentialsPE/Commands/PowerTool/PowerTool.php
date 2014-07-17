<?php
namespace EssentialsPE\Commands\PowerTool;

use EssentialsPE\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class PowerTool extends BaseCommand{
    public function __construct(Loader $plugin){
        parent::__construct($plugin, "powertool", "Toogle PowerTool on the item you're holding", "/powertool <command> <arguments>", ["pt"]);
        $this->setPermission("essentials.command.powertool");
    }

    public function execute(CommandSender $sender, $alias, array $args){
        if(!$this->testPermission($sender)){
            return false;
        }
        if(!$sender instanceof Player){
            $sender->sendMessage(TextFormat::RED . "Please run this command in-game.");
            return false;
        }
        $item = $sender->getInventory()->getItemInHand();
        if($item->getID() == Item::AIR){
            $sender->sendMessage(TextFormat::RED . "You can't assign a command to an empty hand.");
            return false;
        }
        if(count($args) == 0){
            $this->getAPI()->disablePowerToolItem($sender, $item);
            $sender->sendMessage(TextFormat::GREEN . "Command removed from this item.");
        }else{
            $this->getAPI()->setPowerToolItemCommand($sender, $item, implode(" ", $args));
            $sender->sendMessage(TextFormat::GREEN . "Command successfully assigned to this item!");
        }
        return true;
    }
} 