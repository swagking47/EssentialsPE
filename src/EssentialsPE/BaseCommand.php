<?php
namespace EssentialsPE;

use pocketmine\command\Command;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\event\Listener;
use pocketmine\Server;

abstract class BaseCommand extends Command implements PluginIdentifiableCommand, Listener{
    /** @var Loader */
    public $plugin;

    public function __construct(Loader $plugin, $name, $description = "", $usageMessage = null, array $aliases = []){
        parent::__construct($name, $description, $usageMessage, $aliases);
        $this->plugin = $plugin;
    }

    public function getPlugin(){
        return $this->plugin;
    }

    //TODO :P
    public function colorMessage($message){
        return str_replace(["&0", "&1", "&2", "&3, &4, &5, &6, &7, &8, &9, &a, &b, &c, &d, &e, &f, &k, &l, &m, &n, &o, &r"], ["§0, §1, §2, §3, §4, §5, §6, §7, §8, §9, §a, §b, §c, §d, §e, §f, §k, §l, §m, §n, §o, §r"], $message);
    }

    public function getPlayer($player){
        $r = "";
        foreach(Server::getInstance()->getOnlinePlayers() as $p){
            if($p->getDisplayName() == $player || $p->getName() == $player){
                $r = Server::getInstance()->getPlayerExact($p->getName());
            }
        }
        if($r == ""){
            return false;
        }else{
            return $r;
        }
    }
} 