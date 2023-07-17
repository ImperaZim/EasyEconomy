<?php

namespace ImperaZim\EasyEconomy\event;

use pocketmine\player\Player;

/**
* This event is ALWAYS called when a {@see Player} transfers an amount of money to another {@see Player}
*/
class PlayerTransferMoneyEvent extends PlayerEvent {

  private Player $target;
  private Int $amount;

  public function __construct(Player $source, Player $target, int|float $amount) {
    parent::__construct($source);
    $this->target = $target;
    $this->amount = $amount;
  }

  public function getTarget() : Player {
    return $this->target;
  }

  public function getAmount() : int|float {
    return $this->amount;
  }
}