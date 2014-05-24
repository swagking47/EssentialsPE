<?php
namespace EssentialsPE\Commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class Nick extends Command{
    public function __construct() {
        parent::__construct("nick", "Change your name", "/nick <new nick> [player]", ["nickname"]);
        $this->setPermission("essentials.nick");
    }
    
    public function execute(CommandSender $sender, $alias, array $args) {
        if(!$this->testPermission($sender)){
            return false;
        }
        switch(count($args)){
            case 0:
                if($sender->hasPermission("essentials.nick.use")){
                    if(!($sender instanceof Player)){
                        $sender->sendMessage(TextFormat::RED . "Usage: /nick <new nick> <player>");
                    }else{
                        $sender->sendMessage(TextFormat::RED . "Usage: " . $this->getUsage());
                    }
                    return true;
                }else{
                    $sender->sendMessage(TextFormat::RED . "You don't have permissions to use this command.");
                    return true;
                }
                break;
            case 1:
                if(!($sender instanceof Player)){
                    $sender->sendMessage(TextFormat::RED . "Usage: /nick <new nick> <player>");
                }else{
                    if($sender->hasPermission("essentials.nick.use")){
                        if(!($sender instanceof Player)){
                            return false;
                        }else{
                            $sender->setDisplayName($args[0]);
                            $sender->sendMessage("Your name is now: $args[0]");
                        }
                        return true;
                    }else{
                        $sender->sendMessage(TextFormat::RED . "You don't have permissions to use this command.");
                        return true;
                    }
                }
                break;
            case 2:
                if($sender->hasPermission("essentials.nick.other")){
                    $player = Server::getInstance()->getPlayer($args[1]);
                    if($player instanceof Player && $player->isOnline()){
                        $player->setDisplayName($args[0]);
                        $player->sendMessage("Your name is now: $args[0]");
                        $sender->sendMessage("$args[1] is now named $args[0]");
                    }else{
                        $sender->sendMessage(TextFormat::RED . "[Error] Player not found");
                    }
                    return true;
                }else{
                    $sender->sendMessage(TextFormat::RED . "You don't have permissions to use this command.");
                    return true;
                }
                break;
        }
        return true;
    }
}
