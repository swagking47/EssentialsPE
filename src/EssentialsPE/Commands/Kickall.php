<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class Kickall extends BaseCommand{
    public function __construct(Loader $plugin){
        parent::__construct("kickall", "Kick all the players", "/kickall [reason]");
        $this->setPermission("essentials.kickall");
        $this->plugin = $plugin;
    }

    public function execute(CommandSender $sender, $alias, array $args){
        if(!$this->testPermission($sender)){
            return false;
        }
        if(count($args) == 0){
            $reason = "";
        }else{
            $reason = $args[0];
        }
        foreach(Server::getInstance()->getOnlinePlayers() as $p){
            if($p != $sender){
                $p->kick($reason);
            }
        }
        $sender->sendMessage(TextFormat::AQUA . "Kicked all the players!");
    }
} 