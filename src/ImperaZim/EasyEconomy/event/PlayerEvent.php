<?php

namespace ImperaZim\EasyEconomy\event;

use pocketmine\event\Event;
use pocketmine\player\Player;

abstract class PlayerEvent extends Event {

    private Player $player;

    public function __construct(Player $player) {
        $this->player = $player;
    }

    public function getPlayer() : Player {
        return $this->player;
    }
}
