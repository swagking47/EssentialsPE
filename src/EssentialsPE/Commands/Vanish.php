<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Vanish extends BaseCommand{
    public $vanished = [];

    public function __construct(Loader $plugin){
        parent::__construct($plugin, "vanish", "Hide from other players!", "/vanish", ["v"]);
        $this->setPermission("essentials.command.vanish.use");
    }

    public function execute(CommandSender $sender, $alias, array $args){
        if(!$this->testPermission($sender)){
            return false;
        }
        if(count($args) > 1){
            $sender->sendMessage(TextFormat::RED . "Usage: " . $this->getUsage());
        }else{
            switch(count($args)){
                case 0:
                    if(!$sender instanceof Player){
                        $sender->sendMessage(TextFormat::RED . "Usage: " . $this->getUsage());
                    }else{
                        $this->switchVanish($sender);
                        if(!$this->isVanished($sender)){
                            $sender->sendMessage(TextFormat::GRAY . "You're now visible");
                        }else{
                            $sender->sendMessage(TextFormat::GRAY . "You're now vanished!");
                        }
                    }
                    break;
                case 1:
                    $player = $this->getPlayer($args[0]);
                    if($player == false){
                        $sender->sendMessage(TextFormat::RED . "[Error] Player not found.");
                    }else{
                        $this->switchVanish($player);
                        if(!$this->isVanished($player)){
                            $player->sendMessage(TextFormat::GRAY . "You're now visible");
                            $sender->sendMessage(TextFormat::GRAY . "$args[0] is now visible");
                        }else{
                            $player->sendMessage(TextFormat::GRAY . "You're now vanished!");
                            $sender->sendMessage(TextFormat::GRAY . "$args[0] is now vanished!");
                        }
                    }
                    break;
            }
        }
        return true;
    }
    public function switchVanish(Player $player){
        if(!array_key_exists($player->getName(), $this->vanished)){
            array_push($this->vanished, $player->getName());
            return "muted";
        }else{
            unset($this->vanished[array_search($player->getName(), $this->muted)]);
            return "unmuted";
        }
    }

    public function isVanished(Player $player){
        if(!array_key_exists($player->getName(), $this->vanished)){
            return false;
        }else{
            return true;
        }
    }
} 