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
        $this->setPermission("essentials.heal");
        $this->plugin = $plugin;
    }
    
    public function execute(CommandSender $sender, $alias, array $args) {
        if(!$this->testPermission($sender)){
        }
        switch(count($args)){
            case 0:
                if($sender->hasPermission("essentials.heal.use")){
                    if(!($sender instanceof Player)){
                        $sender->sendMessage(TextFormat::RED . "Usage: /heal <player>");
                    }else{
                        $sender->setHealth(20);
                        $sender->sendMessage(TextFormat::GREEN . "You have been healed!");
                    }
                    return true;
                }else{
                    $sender->sendMessage(TextFormat::RED . "You don't have permissions to use this command.");
                    return true;
                }
                break;
            case 1:
                if($sender->hasPermission("essentials.heal.other")){
                    $player = Server::getInstance()->getPlayer($args[0]);
                    if($player instanceof Player && $player->isOnline()){
                        $player->setHealth(20);
                        $player->sendMessage("You have been healed!");
                        $sender->sendMessage("$args[0] has been healed!");
                    }else{
                        $sender->sendMessage(TextFormat::RED . "[Error] Player not found.");
                    }
                    return true;
                }else{
                    $sender->sendMessage("You don't have permissions to use this command.");
                    return true;
                }
                break;
        }
    }
}
