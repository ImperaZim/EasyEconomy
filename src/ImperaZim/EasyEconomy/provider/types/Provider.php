<?php

namespace ImperaZim\EasyEconomy\provider\types;

use pocketmine\player\Player;
use ImperaZim\EasyEconomy\EasyEconomy;

interface Provider {

  public function __construct(EasyEconomy $plugin);

  public function createTable() : void;

  public function getName() : string;

  public function createProfile(Player $player) : bool;

  public function exist(Player $player) : bool;

  public function getMoney(Player $player) : mixed;

  public function setMoney(Player $player, int|float $amount) : bool;

  public function addMoney(Player $player, int|float $amount) : bool;

  public function reduceMoney(Player $player, int|float $amount) : bool;

  public function getAllInOrder() : array;

  public function getAll() : array;

}