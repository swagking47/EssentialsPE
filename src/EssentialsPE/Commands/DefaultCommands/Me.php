<?php
namespace EssentialsPE\Commands\DefaultCommands;

use EssentialsPE\API;
use EssentialsPE\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Me extends BaseCommand{
    public function __construct(Loader $plugin){
        parent::__construct($plugin, "me", "Performs the specified action in chat", "/me <action...>");
        $this->setPermission("pocketmine.command.me");
    }

    public function execute(CommandSender $sender, $alias, array $args){
        if(!$this->testPermission($sender)){
            return true;
        }
        if($sender instanceof Player && $GLOBALS["mutes"][$sender->getName()] == true){
            return false;
        }
        if(count($args) === 0){
            $sender->sendMessage(TextFormat::RED . "Usage: " . $this->usageMessage);
            return false;
        }
        $message = "* ";
        if($sender instanceof Player){
            $message .= $sender->getDisplayName();
        }else{
            $message .= $sender->getName();
        }
        $api = new API();
        $sender->getServer()->broadcastMessage($message . " " . $api->colorMessage(implode(" ", $args)));
        return true;
    }
} 