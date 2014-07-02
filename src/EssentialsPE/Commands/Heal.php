<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Heal extends BaseCommand{
    public function __construct(Loader $plugin){
        parent::__construct($plugin, "heal", "Heal yourself or other player", "/heal [player]");
        $this->setPermission("essentials.command.heal.use");
    }

    public function execute(CommandSender $sender, $alias, array $args){
        if(!$this->testPermission($sender)){
            return false;
        }
        if(count($args) > 1){
            $sender->sendMessage(TextFormat::RED . "Usage: " . $this->getUsage());
        }
        switch(count($args)){
            case 0:
                if(!$sender instanceof Player){
                    $sender->sendMessage(TextFormat::RED . "Usage: " . $this->getUsage());
                }else{
                    $sender->setHealth($sender->getMaxHealth());
                    $sender->sendMessage(TextFormat::GREEN . "You have been healed!");
                }
                break;
            case 1:
                if(!$sender->hasPermission("essentials.command.heal.other")){
                    $sender->sendMessage(TextFormat::RED . $this->getPermissionMessage());
                }else{
                    $player = $this->getPlayer($args[0]);
                    if($player == false){
                        $sender->sendMessage(TextFormat::RED . "[Error] Player not found.");
                    }else{
                        $player->setHealth($player->getMaxHealth());
                        $player->sendMessage(TextFormat::GREEN . "You have been healed!");
                        $sender->sendMessage(TextFormat::GREEN . "$args[0] has been healed!");
                    }
                }
                break;
        }
        return true;
    }
} 