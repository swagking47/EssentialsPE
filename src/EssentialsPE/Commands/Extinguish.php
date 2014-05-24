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
        parent::__construct("extinguish", "Extinguish a player", "/extinguish <player>", ["ex"]);
        $this->setPermission("essentials.extinguish");
        $this->plugin = $plugin;
    }
    
    public function execute(CommandSender $sender, $alias, array $args) {
        if(!$this->testPermission($sender)){
            return false;
        }
        switch(count($args)){
            case 0:
                if(!($sender instanceof Player)){
                    $sender->sendMessage(TextFormat::RED . "Usage: " . $this->getUsage());
                }else{
                    $sender->extinguish();
                    $sender->sendMessage(TextFormat::AQUA . "Extinguished!");
                }
            case 1:
                if(!($args[0] instanceof Player)){
                    $sender->sendMessage(TextFormat::RED . "[Error] Player not found.");
                }else{
                    $args[0]->extinguish();
                    $sender->sendMessage(TextFormat::AQUA . "Player extinguished!");
                }
                break;
        }
    }
}
