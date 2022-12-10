<?php

namespace ImperaZim\EasyEconomy\Event\PlayerEvent;

use pocketmine\event\Listener;
use ImperaZim\EasyEconomy\EasyEconomy;
use pocketmine\event\player\PlayerJoinEvent;
use ImperaZim\EasyEconomy\Functions\DataBase\DataBase;

class JoinEvent implements Listener {

 public function Event(PlayerJoinEvent $event) : void {
  DataBase::open()->createProfile($event->getPlayer());
 }

}

 
