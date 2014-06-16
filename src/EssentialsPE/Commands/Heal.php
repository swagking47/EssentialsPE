<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use EssentialsPE\Loader;

class Heal extends BaseCommand{
    public function __construct(Loader $plugin) {
        parent::__construct("heal", "Heal yourself or other players", "/heal [player]");
        $this->setPermission("essentials.heal.use");
        $this->plugin = $plugin;
    }
    
    public function execute(CommandSender $sender, $alias, array $args) {
        if(!$this->testPermission($sender)){
            return false;
        }
        if(count($args) > 1){
            if(!$this->isPlayer($sender)){
                $sender->sendMessage(TextFormat::RED . "Usage: /heal <player>");
            }else{
                $sender->sendMessage(TextFormat::RED . "Usage: /heal [player]");
            }
        }
        switch(count($args)){
            case 0:
                if(!$sender instanceof Player){
                    $sender->sendMessage(TextFormat::RED . "Usage: /heal <player>");
                }else{
                    $sender->setHealth($sender->getMaxHealth());
                    $sender->sendMessage(TextFormat::GREEN . "You have been healed!");
                }
                break;
            case 1:
                if(!$sender->hasPermission("essentials.heal.other")){
                    $sender->sendMessage(TextFormat::RED . $this->getPermissionMessage());
                }else{
                    $player = $this->getPlayer($args[0]);
                    if($player == false){
                        $sender->sendMessage(TextFormat::RED . "[Error] Player not found.");
                    }else{
                        $player->setHealth($player->getMaxHealth());
                        $player->sendMessage("You have been healed!");
                        $sender->sendMessage("$args[0] has been healed!");
                    }
                }
                break;
        }
        return true;
    }
}
