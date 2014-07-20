<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class TempBan extends BaseCommand{
    public function __construct(Loader $plugin){
        parent::__construct($plugin, "tempban", "Temporary bans the specified player", "/tempban <player> <time>");
        $this->setPermission("essentials.command.tempban");
    }

    public function execute(CommandSender $sender, $alias, array $args){
        if(!$this->testPermission($sender)){
            return false;
        }
        if(count($args) < 2){
            $sender->sendMessage(TextFormat::RED . "Usage: " . $this->getUsage());
            return false;
        }
        $player = $this->getAPI()->getPlayer($args[0]);
        if($player === false){
            $sender->sendMessage(TextFormat::RED . "[Error] Player not found");
            return false;
        }
        $years = date("Y", time()); //Numeric representation of the year (4 digits)
        $months = date("n", time()); //Month number of the year
        $weeks = date("W", time()); //Week number of the year
        $days = date("z", time()); //Day number of the year
        $hours = date("g", time()); //12 hour format
        $minutes = date("i", time()); //Minutes with leading zeros
        $seconds = date("s", time()); //Seconds with leading zeros
        foreach($args as $a){
            if(substr($a, -1, 1) == "y"){
                $years = substr($a, 0, strlen($a) - 1);
            }elseif(substr($a, -1, 1) == "mo"){
                $mo = substr($a, 0, strlen($a) - 2);
            }elseif(substr($a, -1, 1) == "w"){
                $w = substr($a, 0, strlen($a) - 1);
            }elseif(substr($a, -1, 1) == "d"){
                $d = substr($a, 0, strlen($a) - 1);
            }elseif(substr($a, -1, 1) == "h"){
                $h = substr($a, 0, strlen($a) - 1);
            }elseif(substr($a, -1, 1) == "m"){
                $m = substr($a, 0, strlen($a) - 1);
            }elseif(substr($a, -1, 1) == "s"){
                $s = substr($a, 0, strlen($a) - 1);
            }
        }
        $date = date_create_from_format("format", "time");
        //Server::getInstance()->getNameBans()->addBan($player, "reason", "expire date", "source");
        return true;
    }
}
