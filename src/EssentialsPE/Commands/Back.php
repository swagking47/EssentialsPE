<?php
/*
 * TO-DO, We need to wait until pocketmine can handle player teleport events :/
 */
namespace EssentialsPE\Commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

use EssentialsPE\API\Sessions;

class Back extends Command{
    public $sessions;

    public function __construct() {
        parent::__construct("back", "Return to your last position", "/back", ["return"]);
        $this->setPermission("essentials.back");
        
        $this->sessions = new Sessions;
        $this->sessions->setDefault("back", null);
    }
    
    public function execute(CommandSender $sender, $alias, array $args) {
        if(!$this->testPermission($sender)){
            return false;
        }
        if(!$sender instanceof Player){
            $sender->sendMessage(TextFormat::RED . "Please run this command in-game.");
        }else{
            if($this->sessions->get($sender, "back") == null){
                $sender->sendMessage(TextFormat::RED . "No previous point.");
            }else{
                $sender->sendMessage(TextFormat::YELLOW . "Teleporting...");
                $sender->teleport($this->sessions->get($sedner, "back"));
            }
        }
    }
}
