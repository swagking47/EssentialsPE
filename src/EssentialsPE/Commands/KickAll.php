<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class KickAll extends BaseCommand{
    public function __construct(Loader $plugin){
        parent::__construct($plugin, "kickall", "Kick all the players", "/kickall");
        $this->setPermission("essentials.command.kickall");
    }

    public function execute(CommandSender $sender, $alias, array $args){
        if(!$this->testPermission($sender)){
            return false;
        }
        if(count($args) == 0){
            $reason = "Unknown reason";
        }else{
            $reason = implode(" ", $args);
        }
        foreach(Server::getInstance()->getOnlinePlayers() as $p){
            if($p != $sender){
                $p->kick($reason);
            }
        }
        $sender->sendMessage(TextFormat::AQUA . "Kicked all the players!");
        return true;
    }
} 