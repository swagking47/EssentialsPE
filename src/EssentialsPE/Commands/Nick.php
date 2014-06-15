<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use EssentialsPE\Loader;

class Nick extends BaseCommand{
    public $config;

    public function __construct(Loader $plugin) {
        parent::__construct("nick", "Change your name", "/nick <nick> [player]", ["nickname"]);
        $this->setPermission("essentials.nick.use");
        $this->plugin = $plugin;
        $this->config = new Config("plugins/Essentials/Nicknames.yml", Config::YAML);
    }

    public function execute(CommandSender $sender, $alias, array $args) {
        if(!$this->testPermission($sender)){
            return false;
        }
        if(count($args) == 0 || count($args) > 2){
            if(!$sender instanceof Player){
                $sender->sendMessage(TextFormat::RED . "Usage: /nick <nick> <player>");
            }else{
                $sender->sendMessage(TextFormat::RED . "Usage: " . $this->getUsage());
            }
        }else{
            switch(count($args)){
                case 1:
                    if(!$sender instanceof Player){
                        $sender->sendMessage(TextFormat::RED . "Usage: /nick <nick> <player>");
                    }else{
                        $sender->setDisplayName($args[0]);
                        $sender->sendMessage(TextFormat::YELLOW . "Your nick is now: " . TextFormat::RESET . $args[0]);
                    }
                    break;
                case 2:
                    $player = $this->getPlayer($args[1]);
                    if($player == false){
                        $sender->sendMessage(TextFormat::RED . "[Error] Player not found.");
                    }else{
                        $player->setDisplayName($args[0]);
                        $player->sendMessage(TextFormat::YELLOW . "Your nick is now: " . TextFormat::RESET . $args[0]);
                        if(substr($player->getName(), -1, 1) != "s"){
                            $sender->sendMessage(TextFormat::GREEN . $player->getName() . "'s nick changed to $args[0]");
                        }else{
                            $sender->sendMessage(TextFormat::GREEN . $player->getName() . "' nick changed to $args[0]");
                        }
                    }
                    break;
            }
        }
        $this->save();
	    return true;
    }

    private function save(){
        foreach(Server::getInstance()->getOnlinePlayers() as $p){
            if($p->getName() != $p->getDisplayName()){
                $this->config->set($p->getName(), $p->getDisplayName());
            }
        }
        $this->config->save();
    }
}
