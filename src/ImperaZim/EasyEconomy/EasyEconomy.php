<?php

namespace ImperaZim\EasyEconomy;

use pocketmine\utils\Config;
use pocketmine\plugin\PluginBase;
use ImperaZim\EasyEconomy\Event\Events;
use ImperaZim\EasyEconomy\Command\Commands;
use ImperaZim\EasyEconomy\Functions\DataBase\DataBase;

class EasyEconomy extends PluginBase {
 
 public $DEFAULT_MONEY = 1000;
 public static EasyEconomy $instance;

 public static function getInstance() : EasyEconomy {
  return self::$instance;
 }
 
 public function onEnable() : void {
  self::$instance = $this; 
  $this->loadAll();
 }
 
 public function loadAll() : void {
  if(DataBase::checkType()) {
   Events::registerAll();
   Commands::registerAll();
   $this->saveResource('messages.yml');
  } 
 }
 
 public function getMessages() : Config {
  return new Config($this->getDataFolder() . "messages.yml");
 }
 
 /* API FUNCTIONS */
 
 public function getProvinder() {
  return DataBase::open();
 }
 
}
