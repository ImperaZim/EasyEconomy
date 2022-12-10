<?php

namespace ImperaZim\EasyEconomy\Functions\DataBase\Provinder;

use pocketmine\utils\Config;
use pocketmine\player\Player;
use ImperaZim\EasyEconomy\Functions\DataBase\DataBase;

class YAML implements \ImperaZim\EasyEconomy\Functions\DataBase\ProvinderBase {
 
 public function table() {
  return new Config(DataBase::getInstance()->getDataFolder() . "players.yml");
 }
  
 public function createTable() {
  (new Config(DataBase::getInstance()->getDataFolder() . "players.yml", Config::YAML, ["players" => []]))->save();
 }
 
 public function createProfile(Player $player) {
  $perfil = (new Config(DataBase::getInstance()->getDataFolder() . "players.yml", Config::YAML, ["players" => [$player->getName() => DataBase::getInstance()->DEFAULT_MONEY]]));
  if (!$this->exist($player)) {
   $perfil->save();
  }  
 }
 
 public function exist(Player $player) {
  return isset($this->table()->getAll()["players"][$player->getName()]);
 }
 
 public function getMoney(Player $player) {
  if ($this->exist($player)) {
   return $this->table()->getNested("players.{$player->getName()}");
  }
  return 0;
 } 
 
 public function setMoney(Player $player, Int $value) {
  if ($this->exist($player)) {
   $config = $this->table();
   $config->setNested("players.{$player->getName()}", $value);
   $config->save(); 
   return true;
  }
  return false;
 } 
 
 public function addMoney(Player $player, Int $value) {
  if ($this->exist($player)) {
   $config = $this->table();
   $money = $this->getMoney($player) + $value;
   $config->setNested("players.{$player->getName()}", $money);
   $config->save(); 
   return true;
  }
  return false;
 } 
 
 public function reduceMoney(Player $player, Int $value) {
  if ($this->exist($player)) {
   if ($this->getMoney($player) < $value) {
    $config = $this->table();
    $config->setNested("players.{$player->getName()}", 0);
    $config->save(); 
   }else{
    $config = $this->table();
    $money = $this->getMoney($player) - $value; 
    $config->setNested("players.{$player->getName()}", $money);
    $config->save(); 
   }
   return true;
  }
  return false;
 } 
 
 public function getAllInOrder() : array {
  $id = 1;
  $table = [];
  foreach ($this->getAll()["players"] as $name => $money) {
   if ($id <= 10) {
    $table[$money+$id] = "{$name}:{$money}";
   }
   $id = $id + 1;
  }
  krsort($table);
  $explode = "";
  foreach ($table as $ident => $data) {
   $user = explode(":", $data);
   $explode .= "{$user[0]}:{$user[1]}&";
  }
  return explode("&", $explode);
 } 
 
 public function getAll() : array {
  return $this->table()->getAll();
 }
 
}
 