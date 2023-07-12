<?php

namespace ImperaZim\EasyEconomy\event;

use pocketmine\player\Player;
use ImperaZim\EasyEconomy\EasyEconomy;

/**
* This event is ALWAYS called when a {@see Player}'s money amount is updated
*/
class PlayerMoneyUpdateEvent extends PlayerEvent {

  public function __construct(Player $source) {
    parent::__construct($source);
    $this->provider = EasyEconomy::getInstance()->getProvider();
  }
  
  public function getNewBalance() : int|float {
    return $this->provider->getMoney($this->getPlayer());
  }
}