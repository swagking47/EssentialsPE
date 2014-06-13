<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class ClearInventory extends BaseCommand{
    public function __construct(Loader $plugin){
        parent::__construct("clearinventory", "Clear your/other inventory", "/clearinventory [player]", ["ci", "clean", "clearinvent"]);
        $this->setPermission("essentials.clearinventory.use");
        $this->plugin = $plugin;
    }

    public function execute(CommandSender $sender, $alias, array $args){
        if(!$this->testPermission($sender)){
            return false;
        }
        if(count($args) > 1){
            if(!$sender instanceof Player){
                $sender->sendMessage(TextFormat::RED . "Usage: /clearinventory <player>");
            }else{
                $sender->sendMessage(TextFormat::RED . "Usage: " . $this->getUsage());
            }
        }
        switch(count($args)){
            case 0:

                break;
            case 1:
                if(!$sender->hasPermission("essentials.clearinventory.other")){
                    $sender->sendMessage(TextFormat::RED . $this->getPermissionMessage());
                }else{
                    $player = $this->getPlayer($args[0]);
                    if($player == false){
                        $sender->sendMessage(TextFormat::RED . "[Error] Player not found.");
                    }else{
                        $player->getInventory()->clearAll();
                    }
                }
                break;
        }
    }
} 