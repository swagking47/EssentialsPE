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
            return false;
        }
        switch(count($args)){
            case 0:
                if(!$sender instanceof Player){
                    $sender->sendMessage(TextFormat::RED . "Usage: /nick <nick> <player>");
                }else{
                    $sender->sendMessage(TextFormat::RED . "Usage: " . $this->getUsage());
                }
                break;
            case 1:
                if(!$sender instanceof Player){
                    $sender->sendMessage(TextFormat::RED . "Usage: /nick <nick> <player>");
                }else{
                    $sender->setDisplayName("$args[0]");
                    $sender->sendMessage(TextFormat::GREEN . "Your name is now: " . TextFormat::RESET . "$args[0]");
                }
                break;
            case 2:
                if(!$sender->hasPermission("essentials.nick.other")){
                    $sender->sendMessage(TextFormat::RED . "You don't have permissions to use this command.");
                }else{
                    $player = Server::getInstance()->getPlayer($args[1]);
                    if($player == false){
                        $sender->sendMessage(TextFormat::RED . "[Error] Player not found");
                    }else{
                        $player->setDisplayName("$args[0]");
                        $player->sendMessage(TextFormat::GREEN . "Your name is now: " . TextFormat::RESET . "$args[0]");
                        $sender->sendMessage(TextFormat::GREEN . "$args[1] is now named: " . TextFormat::RESET . "$args[0]");
                    }
                }
        }
    }
}
