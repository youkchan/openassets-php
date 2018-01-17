<?php
require_once "../vendor/autoload.php";
use youkchan\OpenassetsPHP\Openassets;

$openassets = new Openassets();

var_dump($openassets->list_unspent());

