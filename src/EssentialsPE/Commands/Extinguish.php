<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use EssentialsPE\Loader;

class Extinguish extends BaseCommand{
    public function __construct(Loader $plugin) {
        parent::__construct("extinguish", "Extinguish a player", "/extinguish [player]", ["ex"]);
        $this->setPermission("essentials.extinguish.use");
        $this->plugin = $plugin;
    }
    
    public function execute(CommandSender $sender, $alias, array $args) {
        if(!$this->testPermission($sender)){
            return false;
        }
        if(count($args) > 1){
            if(!$sender instanceof Player){
                $sender->sendMessage(TextFormat::RED . "Usage: /extinguish <player>");
            }else{
                $sender->sendMessage(TextFormat::RED . "Usage: " . $this->getUsage());
            }
        }
        switch(count($args)){
            case 0:
                if(!$sender instanceof Player){
                    $sender->sendMessage(TextFormat::RED . "Usage: /extinguish <player>");
                }else{
                    $sender->extinguish();
                    $sender->sendMessage(TextFormat::AQUA . "Extinguished!");
                }
                break;
            case 1:
                if(!$sender->hasPermission("essentials.extinguish.other")){
                    $sender->sendMessage(TextFormat::RED . $this->getPermissionMessage());
                }else{
                    $player = $this->getPlayer($args[0]);
                    if($player == false){
                        $sender->sendMessage(TextFormat::RED . "[Error] Player not found.");
                    }else{
                        $player->extinguish();
                        $sender->sendMessage(TextFormat::AQUA . "$args[0] has been extinguished!");
                    }
                }
                break;
        }
        return true;
    }
}
