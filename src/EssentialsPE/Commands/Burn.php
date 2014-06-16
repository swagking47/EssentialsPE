<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use EssentialsPE\Loader;

class Burn extends BaseCommand{
    public function __construct(Loader $plugin) {
        parent::__construct("burn", "Set any player on fire!", "/burn <player> <seconds>");
        $this->setPermission("essentials.burn");
        $this->plugin = $plugin;
    }
    
    public function execute(CommandSender $sender, $alias, array $args) {
        if(!$this->testPermission($sender)){
            return false;
        }
        if(count($args) != 2){
            $sender->sendMessage(TextFormat::RED . "Usage: " . $this->getUsage());
        }else{
            $player = $this->getPlayer($args[0]);
            if($player == false){
                $sender->sendMessage(TextFormat::RED . "[Error] Player not found.");
            }else{
                if(!is_numeric($args[1])){
                    $sender->sendMessage(TextFormat::RED . "[Error] Invalid time.");
                }else{
                    $player->setOnFire($player);
                    $sender->sendMessage(TextFormat::YELLOW . "$args[0] is now on fire!");
                }
            }
        }
        return true;
    }
}
