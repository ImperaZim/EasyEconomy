<?php

namespace ImperaZim\EasyEconomy\event;

use pocketmine\event\Event;

class TycoonUpdateEvent extends Event {

  private string $old;
  private string $new;

  public function __construct(string $old, string $new) {
    $this->old = $old;
    $this->new = $new;
  }

  public function getOld() : string {
    return $this->old;
  }

  public function getNew() : string {
    return $this->new;
  }
}