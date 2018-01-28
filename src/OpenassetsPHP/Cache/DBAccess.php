<?php
namespace youkchan\OpenassetsPHP\Cache;
use Exception;

class DBAccess
{

    protected $db;

    public function __construct($path) {
        $this->db = new \SQLite3($path);
        $this->setup();
    }

    public function setup() {
        throw new Exception("need setup method implementation");
    }
}
