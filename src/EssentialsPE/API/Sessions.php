<?php
namespace EssentialsPE\API;

class Sessions {
    public static $instance;

    public $sessions = [];
    public $default = [];

    public function __construct(){
        self::$instance = $this;
    }
} 