<?php
namespace EssentialsPE\API;

use EssentialsPE\Loader;
use pocketmine\utils\Config;

class Warps {
    public $config;

    public function __construct(){
        $this->config = new Config(Loader::DIRECTORY . "Warps.yml", Config::YAML);
    }

    public function set(){}

    public function tp(){}

    public function warpsList(){}
} 