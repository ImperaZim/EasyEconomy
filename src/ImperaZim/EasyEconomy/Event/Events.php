<?php

namespace ImperaZim\EasyEconomy\Event;

use ImperaZim\EasyEconomy\Event\PlayerEvent\JoinEvent;

class Events extends \ImperaZim\EasyEconomy\EasyEconomy {
 
 public static function registerAll() : void {
   $events = [
    JoinEvent::class
   ];
   foreach ($events as $event) {
    self::getInstance()->getServer()->getPluginManager()->registerEvents(new $event(), self::getInstance());
   }
  } 
 
} 
