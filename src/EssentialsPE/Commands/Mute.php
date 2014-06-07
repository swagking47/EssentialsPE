<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

use EssentialsPE\API\Sessions;
use EssentialsPE\Loader;



class Mute extends BaseCommand{
    public static $mutes = [];

    public function __construct(Loader $plugin){
        parent::__construct("mute", "Prevent a player from chatting", "/mute <player>", ["silence"]);
        $this->setPermission("essentials.mute.use");
        $this->plugin = $plugin;
    }

    public function execute(CommandSender $sender, $alias, array $args){
        if($this->testPermission($sender)){
        }
        if(count($args) != 1){
            $sender->sendMessage(TextFormat::RED . $this->getPermissionMessage());
        }
        $player = Server::getInstance()->getPlayer($args[0]);
        if(!$this->isPlayer($player)){
            $sender->sendMessage(TextFormat::RED . "[Error] Player not found.");
        }else{
            //TODO
        }
    }
} 