<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use EssentialsPE\Loader;

class Nick extends BaseCommand{
    public function __construct(Loader $plugin) {
        parent::__construct("nick", "Change your name", "/nick <nick> [player]", ["nickname"]);
        $this->setPermission("essentials.nick.use");
        $this->plugin = $plugin;
    }
    
    public function execute(CommandSender $sender, $alias, array $args) {
        if(!$this->testPermission($sender)){
        }
        if(count($args) == 0 || count($args) > 2){
            $sender->sendMessage(TextFormat::RED . "Usage: " . $this->getUsage());
        }else{
            switch(count($args)){
                case 1:
                    if(!$sender instanceof Player){
                        $sender->sendMessage(TextFormat::RED . "Usage: /nick <nick> <player>");
                    }else{
                        $sender->setDisplayName($args[0]);
                        $sender->sendMessage(TextFormat::YELLOW . "Your nick is now: " . TextFormat::RESET . $args[0]);
                    }
                    break;
                case 2:
                    $player = Server::getInstance()->getPlayer($args[1]);
                    if($player instanceof Player && $player->isOnline()){
                        $player->setDisplayName($args[0]);
                        $player->sendMessage(TextFormat::YELLOW . "Your nick is now: " . TextFormat::RESET . $args[0]);
                        if(substr($player->getName(), -1, 1) != "s"){
                            $sender->sendMessage(TextFormat::GREEN . $player->getName() . "'s nick changed to $args[0]");
                        }else{
                            $sender->sendMessage(TextFormat::GREEN . $player->getName() . "' nick changed to $args[0]");
                        }
                    }else{
                        $sender->sendMessage(TextFormat::RED . "[Error] Player not found.");
                    }
                    break;
            }
        }
    }
}
