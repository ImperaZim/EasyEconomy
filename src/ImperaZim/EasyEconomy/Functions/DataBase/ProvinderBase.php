<?php

namespace ImperaZim\EasyEconomy\Functions\DataBase; 

use pocketmine\player\Player; 

interface ProvinderBase {
 
 public function table();
 
 public function createTable();
 
 public function createProfile(Player $player);
 
 public function exist(Player $player);
 
 public function getMoney(Player $player);
 
 public function setMoney(Player $player, Int $value);
 
 public function addMoney(Player $player, Int $value);
 
 public function reduceMoney(Player $player, Int $value);
 
 public function getAllInOrder() : array;
 
 public function getAll() : array;
 
}