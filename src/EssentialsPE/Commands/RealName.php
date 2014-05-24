<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use EssentialsPE\Loader;

class RealName extends BaseCommand{
    public function __construct(Loader $plugin) {
        parent::__construct("realname", "See a player realname", "/realname <player>");
        $this->setPermission("essentials.realname");
        $this->plugin = $plugin;
    }
    
    public function execute(CommandSender $sender, $alias, array $args){
        if(!$this->testPermission($sender)){
            return false;
        }
        if(count($args) == 0 || count($args) > 1){
            $sender->sendMessage(TextFormat::RED . "Usage: " . $this->getUsage());
        }else{
            $player = Server::getInstance()->getPlayer($args[0]);
            if(!$player instanceof Player){
                $sender->sendMessage(TextFormat::RED . "[Error] Player not found.");
            }else{
                if(substr($args[0], -1, 1) != "s"){
                    $sender->sendMessage(TextFormat::YELLOW . "$args[0]'s real name is: " . TextFormat::AQUA . $player->getName());
                }else{
                    $sender->sendMessage(TextFormat::YELLOW . "$args[0]' real name is: " . TextFormat::AQUA . $player->getName());
                }
            }
        }
    }
}
