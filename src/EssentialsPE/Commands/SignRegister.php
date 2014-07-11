<?php
namespace EssentialsPE\Commands;

use EssentialsPE\API;
use EssentialsPE\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\tile\Sign;
use pocketmine\utils\TextFormat;

class SignRegister extends BaseCommand{
    public function __construct(Loader $plugin){
        parent::__construct($plugin, "signregister", "Register a special sign", "/signregister [warp|teleport]", ["signreg", "sreg"]);
        $this->setPermission("essentials.command.signregister");
    }

    public function execute(CommandSender $sender, $alias, array $args){
        if(!$this->testPermission($sender)){
            return false;
        }
        if(!$sender instanceof Player){
            $sender->sendMessage(TextFormat::RED . "Please run this command in-game.");
        }
        if(count($args) < 1){
            $sender->sendMessage(TextFormat::RED . "Usage: " . $this->getUsage());
            return false;
        }
        $api = new API();
        switch($args[0]){
            case "warp":
                if(count($args) != 2){
                    $sender->sendMessage(TextFormat::RED . "Usage: /signregister warp <warp name>");
                    return false;
                }
                if(!$api->warpExist($args[0])){
                    $sender->sendMessage(TextFormat::RED . "[Error] Warp $args[0] doesn't exist.");
                }else{
                    $GLOBALS["signregister"][$sender->getName()]["warp"] = $args[0];
                    $GLOBALS["signregister"][$sender->getName()]["teleport"] = false;
                    $sender->sendMessage(TextFormat::AQUA . "Done! Now tap the rign you want to register...");
                }
                return true;
                break;
            case "teleport":
            case "tp":
                if(count($args) != 4){
                    $sender->sendMessage(TextFormat::RED . "Usage: /signregister <teleport|tp> <X> <Y> <Z>");
                    return false;
                }
                if(!is_numeric($args[1]|$args[2]|$args[3])){
                    $sender->sendMessage(TextFormat::RED . "[Error] Coordinates must be numbers!");
                    return false;
                }
                $GLOBALS["signregister"][$sender->getName()]["teleport"]["x"] = $args[1];
                $GLOBALS["signregister"][$sender->getName()]["teleport"]["y"] = $args[2];
                $GLOBALS["signregister"][$sender->getName()]["teleport"]["z"] = $args[3];
                $GLOBALS["signregister"][$sender->getName()]["warp"] = false;
            $sender->sendMessage(TextFormat::AQUA . "Done! Now tap the rign you want to register...");
                return true;
                break;
            default:
                $sender->sendMessage(TextFormat::RED . "Usage: " . $this->getUsage());
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

        //Register a Warp/Teleport Sign
        if($block instanceof Sign){
            $text = $block->getText();
            if($GLOBALS["signregister"][$player->getName()]["warp"] !== false || $GLOBALS["signregister"][$player->getName()]["teleport"] !== false){
                //Register
                if($GLOBALS["signregister"][$player->getName()]["warp"] !== false){
                    $text[0] = TextFormat::LIGHT_PURPLE . "[Warp]";
                    $text[1] = $GLOBALS["signregister"][$player->getName()]["warp"];
                }elseif($GLOBALS["signregister"][$player->getName()]["teleport"] !== false){
                    $text[0] = TextFormat::LIGHT_PURPLE . "[Teleport]";
                    $text[1] = $GLOBALS["signregister"][$player->getName()]["teleport"]["x"];
                    $text[2] = $GLOBALS["signregister"][$player->getName()]["teleport"]["y"];
                    $text[3] = $GLOBALS["signregister"][$player->getName()]["teleport"]["z"];
                }
                $block->scheduleUpdate();
            }else{
                //Warp
                if($text[0] == TextFormat::LIGHT_PURPLE . "[Warp]"){
                    $api = new API();
                    $player->sendMessage(TextFormat::YELLOW . "Teleporting to warp: $text[1]");
                    $api->tpWarp($player, $text[1]);
                }elseif($text[0] == TextFormat::LIGHT_PURPLE . "[Teleport]"){
                    $player->sendMessage(TextFormat::YELLOW . "Teleporting...");
                    $player->teleport(new Vector3($text[1], $text[2], $text[3]));
                }
            }
        }
    }
} 