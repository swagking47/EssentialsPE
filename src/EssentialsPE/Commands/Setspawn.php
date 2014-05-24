<?php
//NOTE: Spawn Changer doesn't work at the momment :P
namespace EssentialsPE\Commands;

use EssentialsPE\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\level\Level;
use pocketmine\Player;
use pocketmine\level\Position;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use pocketmine\math\Vector3;
use EssentialsPE\Loader;

class Setspawn extends BaseCommand{
    public function __construct(Loader $plugin) {
        parent::__construct("setspawn", "Change your server spawn", "/setspawn");
        $this->setPermission("essentials.setspawn");
        $this->plugin = $plugin;
    }
    
    public function execute(CommandSender $sender, $alias, array $args) {
        if(!$this->testPermission($sender)){
            return false;
        }
        if(!$sender instanceof Player){
            $sender->sendMessage(TextFormat::RED . "Please run this command in-game.");
        }else{
            $pos = $sender->getPosition();
            $pos->level->setSpawn($pos);
            Server::getInstance()->setDefaultLevel($pos->level);
            $sender->sendMessage(TextFormat::YELLOW . "Spawn changed!");
        }
    }
}
