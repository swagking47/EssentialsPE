<?php
namespace EssentialsPE\Commands;

use EssentialsPE\API\Sessions;
use EssentialsPE\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use EssentialsPE\Loader;



class Mute extends BaseCommand{

    public function __construct(Loader $plugin){
        parent::__construct("mute", "Prevent a player from chatting", "/mute <player>", ["silence"]);
        $this->setPermission("essentials.mute.use");
        $this->plugin = $plugin;
        Sessions::$instance->default["mute"] = false;
    }

    public function execute(CommandSender $sender, $alias, array $args){
        if(!$this->testPermission($sender)){
            return false;
        }
        if(count($args) != 1){
            $sender->sendMessage(TextFormat::RED . $this->getUsage());
        }
        $player = $this->getPlayer($args[0]);
        if($player == false){
            $sender->sendMessage(TextFormat::RED . "[Error] Player not found.");
        }else{
            if($args[0] instanceof Player){
                if($args[0]->hasPermission("essentials.mute.exempt")){
                    $sender->sendMessage(TextFormat::DARK_RED . "$player can't be muted");
                }else{
                    $this->mute($sender, $args[0]);
                }
            }
        }
    }

    //TODO Test and fix ...
    private function mute(CommandSender $sender, Player $player){
        if(Sessions::$instance->sessions[$player->getName()]["mute"] == false){
            Sessions::$instance->sessions[$player->getName()]["mute"] = true;
            $sender->sendMessage(TextFormat::YELLOW . $player->getName() . " has been muted!");
        }else{
            Sessions::$instance->sessions[$player->getName()]["mute"] = false;
            $sender->sendMessage(TextFormat::YELLOW . $player->getName() . " has been unmuted!");
        }
    }
} 