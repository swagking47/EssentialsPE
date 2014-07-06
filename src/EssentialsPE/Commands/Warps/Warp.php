<?php
namespace EssentialsPE\Commands\Warps;

use EssentialsPE\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;

class Warp extends BaseCommand{
    public function __construct(Loader $plugin){
        parent::__construct($plugin, "warp", "Teleport to a warp", "/warp [name [player]]", ["warps"]);
        $this->setPermission("essentials.command.warp.use");
    }

    public function execute(CommandSender $sender, $alias, array $args){
        if(!$this->testPermission($sender)){
            return false;
        }
        //TODO
        return true;
    }
} 