<?php

/*
 * NOTE: THIS WILL NOT WORK UNTIL POCKETMINE CAN HANDLE ENTITY HEALTH CHANGE
 */

namespace EssentialsPE\Commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

use EssentialsPE\API\Sessions;

class God extends Command{
    public $sessions;
    
    public function __construct() {
        parent::__construct("god", "Toggles god mode", "/god [payer[on|off]]", ["godmode", "tgm"]);
        $this->setPermission("essentials.god");
        
        $this->sessions = new Sessions();
        $this->sessions->setDefault("god", false);
    }
    
    public function execute(CommandSender $sender, $alias, array $args) {
        if(!$this->testPermission($sender)){
            return false;
        }
        
        switch(count($args)){
            case 0:
                if($sender->hasPermission("essentials.god.use")){
                    if(!$sender instanceof Player){
                        $sender->sendMessage(TextFormat::RED . "Usage: /god <player> <on|off>");
                    }else{
                        if($this->sessions->get($sender, "god") == true){
                            $this->sessions->set($sender, "god", false);
                            $sender->sendMessage(TextFormat::GREEN . "God mode disabled!");
                        }else{
                            $this->sessions->set($sender, "god", true);
                            $sender->sendMessage(TextFormat::GREEN . "God mode enabled!");
                        }
                    }
                }else{
                    $sender->sendMessage(TextFormat::RED . "You don't have permissions to use this command.");
                }
                break;
            case 1:
                if($sender->hasPermission("essentials.god.other")){
                    $sender->sendMessage(TextFormat::RED . "Usage: /god <player> <on|off>");
                }else{
                    $sender->sendMessage(TextFormat::RED . "You don't have permissions to use this command.");
                }
                break;
            case 2:
                if($sender->hasPermission("essentials.god.other")){
                    if(!$args[0] instanceof Player){
                        $sender->sendMessage(TextFormat::RED . "[Error] Player not found.");
                    }else{
                        $player = Server::getInstance()->getPlayer($args[0]);
                        switch($args[1]){
                            case "on":
                                if($this->sessions->get($player, "god") == false){
                                    $this->sessions->set($player, "god", true);
                                    $player->sendMessage(TextFormat::GREEN . "God mode enabled!");
                                    $sender->sendMessage(TextFormat::GREEN . "God mode enabled for $args[0]");
                                }
                                break;
                            case "off":
                                if($this->sessions->get($player, "god") == true){
                                    $this->sessions->set($player, "god", false);
                                    $player->sendMessage(TextFormat::GREEN . "God mode disabled!");
                                    $sender->sendMessage(TextFormat::GREEN . "God mode disabled for $args[0]");
                                }
                                break;
                        }
                    }
                }else{
                    $sender->sendMessage(TextFormat::RED . "You don't have permissions to use this command.");
                }
                break;
        }
    }
}
