<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Jump extends BaseCommand{
    public function __construct(Loader $plugin){
        parent::__construct($plugin, "jump", "Teleport you to the block you're looking at", "/jump", ["j", "jumpto"]);
        $this->setPermission("essentials.command.jump");
    }

    public function execute(CommandSender $sender, $alias, array $args){
        if(!$this->testPermission($sender)){
            return false;
        }
        if(!$sender instanceof Player){
            $sender->sendMessage(TextFormat::RED . "Please run this command in-game");
            return false;
        }
        if(count($args) > 0){
            $sender->sendMessage(TextFormat::RED . "Usage: " . $this->getUsage());
        }

        $block = $sender->getDirectionVector();
        $pos = new Vector3($block->x, $block->y + 1, $block->z);
        if(($pos->x || $pos->z) <= 0 || ($pos->y <= 0 || $pos->y > 128)){
            $sender->sendMessage(TextFormat::RED . "You can't be teleported to the void");
            return false;
        }

        $sender->sendMessage(TextFormat::YELLOW . "Teleporting...");
        $sender->teleport($pos);
        return true;
    }
} 