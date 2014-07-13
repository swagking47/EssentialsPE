<?php
namespace EssentialsPE\Commands;

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
        switch($args[0]){
            case "warp":
                if(count($args) != 2){
                    $sender->sendMessage(TextFormat::RED . "Usage: /signregister warp <warp name>");
                    return false;
                }
                if(!$this->plugin->warpExist($args[0])){
                    $sender->sendMessage(TextFormat::RED . "[Error] Warp $args[0] doesn't exist.");
                    return false;
                }
                $this->plugin->enableWarpSignRegistration($sender, $args[0]);
                $this->plugin->disableTPSignRegistration($sender);
                $sender->sendMessage(TextFormat::AQUA . "Done! Now tap the sign you want to register...");
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
                $coords = new Vector3($args[1], $args[2], $args[3]);
                $this->plugin->enableTPSignRegistration($sender, $coords);
                $this->plugin->disableWarpSignRegistration($sender);
                $sender->sendMessage(TextFormat::AQUA . "Done! Now tap the sign you want to register...");
                return true;
                break;
            default:
                $sender->sendMessage(TextFormat::RED . "Usage: " . $this->getUsage());
                break;
        }
        return true;
    }
} 