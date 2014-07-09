<?php
namespace EssentialsPE\Commands\Warps;

use EssentialsPE\API\Warps;
use EssentialsPE\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\Player;
use pocketmine\tile\Sign;
use pocketmine\utils\TextFormat;

class Warp extends BaseCommand{
    /*
     * Information on this array is set like:
     * Player Name => Warp Name
     *
     * This is to help registering signs, may change in the future
     */
    private $register = [];
    public $warp;

    public function __construct(Loader $plugin){
        parent::__construct($plugin, "warp", "Teleport to a warp", "/warp [name [player]]", ["warps"]);
        $this->setPermission("essentials.command.warp.use");
    }

    public function execute(CommandSender $sender, $alias, array $args){
        if(!$this->testPermission($sender)){
            return false;
        }
        switch(count($args)){
            case 0:
                if(!$sender instanceof Player){
                    $sender->sendMessage(TextFormat::RED . "Usage: /warp <name> <player>");
                    return false;
                }
                $sender->sendMessage(TextFormat::RED . "Usage: /warp [sign|name] [[warp name]|[player]]");
                return true;
                break;
            case 1:
                if(!$sender instanceof Player){
                    $sender->sendMessage(TextFormat::RED . "Usage: /warp <name> <player>");
                    return false;
                }
                if($args[0] == "sign"){
                    $sender->sendMessage(TextFormat::RED . "Usage: /warp sign <warp name>");
                    return false;
                }else{
                    $warp = new Warps($sender);
                    if(!$warp->exist($args[0])){
                        $sender->sendMessage(TextFormat::RED . "[Error] Unknown warp name.");
                    }else{
                        $warp->tp($args[0]);
                        $sender->sendMessage(TextFormat::YELLOW . "Teleporting");
                    }
                }
                return true;
                break;
            case 2:
                if($args[0] == "sign"){
                    if(!$sender instanceof Player){
                        $sender->sendMessage(TextFormat::RED . "You can't register a Warp Sign from console.");
                        return false;
                    }
                    $warp = new Warps($sender);
                    if(!$warp->exist($args[0])){
                        $sender->sendMessage(TextFormat::RED . "[Error] Unknown warp name.");
                    }else{
                        $this->register[$sender->getName()] = $args[0];
                        $sender->sendMessage(TextFormat::YELLOW . "Now tap the desired sign to register...");
                    }
                }else{
                    $player = $this->getPlayer($args[1]);
                    if($player == false){
                        $sender->sendMessage(TextFormat::RED . "[Error] Player not found.");
                    }else{
                        $warp = new Warps($player);
                        if(!$warp->exist($args[0])){
                            $sender->sendMessage(TextFormat::RED . "[Error] Unknown warp name.");
                        }else{
                            $warp->tp($args[0]);
                            $sender->sendMessage(TextFormat::YELLOW . "Teleporting $args[1] to warp: $args[0]");
                            $player->sendMessage(TextFormat::YELLOW . "Teleporting to warp: $args[0]");
                        }
                    }
                }
                return true;
                break;
        }
        return true;
    }

    /**
     * @param PlayerInteractEvent $event
     */
    public function onBlockTap(PlayerInteractEvent $event){
        $player = $event->getPlayer();
        $block = $event->getBlock();

        //Register a Sign & Teleport
        if($block instanceof Sign){
            $text = $block->getText();
            if(isset($this->register[$player->getName()])){
                //Register
                $text[0] = "[Warp]";
                $text[1] = $this->register[$player->getName()];
                $block->scheduleUpdate();
            }else{
                //Teleport
                if($text[0] == "[Warp]"){
                    $warp = new Warps($player);
                    $player->sendMessage(TextFormat::YELLOW . "Teleporting...");
                    $warp->tp($text[1]);
                }
            }
        }
    }
} 