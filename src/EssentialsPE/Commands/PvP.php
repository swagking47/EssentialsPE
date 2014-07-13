<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class PvP extends BaseCommand{
    public function __construct(Loader $plugin){
        parent::__construct($plugin, "pvp", "Toggle PvP on/off", "/pvp <on|off>");
        $this->setPermission("essentials.command.pvp");
    }

    public function execute(CommandSender $sender, $alias, array $args){
        if(!$this->testPermission($sender)){
            return false;
        }
        if(!$sender instanceof Player){
            $sender->sendMessage(TextFormat::RED . "Please run this command in-game");
            return false;
        }
        if(count($args) != 1){
            $sender->sendMessage(TextFormat::RED . "Usage: " . $this->getUsage());
            return false;
        }
        switch($args[0]){
            case "on":
                if(!$this->plugin->isPvPEnabled($sender)){
                    $this->plugin->switchPvP($sender);
                    $sender->sendMessage(TextFormat::GREEN . "PvP enabled!");
                }
                return true;
                break;
            case "off":
                if($this->plugin->isPvPEnabled($sender)){
                    $this->plugin->switchPvP($sender);
                    $sender->sendMessage(TextFormat::GREEN . "PvP disabled!");
                }
                return true;
                break;
        }
        return true;
    }
} 