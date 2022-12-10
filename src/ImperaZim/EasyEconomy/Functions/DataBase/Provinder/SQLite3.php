<?php

namespace ImperaZim\EasyEconomy\Functions\DataBase\Provinder;

use pocketmine\player\Player;
use ImperaZim\EasyEconomy\Functions\DataBase\DataBase;

class SQLite3 implements \ImperaZim\EasyEconomy\Functions\DataBase\ProvinderBase {
 
 public function table() {
  return new \SQLite3(DataBase::getInstance()->getDataFolder() . "players.db");
 }
  
 public function createTable() {
  $this->table()->exec("CREATE TABLE IF NOT EXISTS profile(name TEXT, money INT)"); 
 }
 
 public function createProfile(Player $player) {
  $perfil = $this->table()->prepare("INSERT INTO profile(name, money) VALUES (:name, :money)");
  $perfil->bindValue(":name", $player->getName()); 
  $perfil->bindValue(":money", DataBase::getInstance()->DEFAULT_MONEY);
  if (!$this->exist($player)) {
   $perfil->execute();
  }  
 }
 
 public function exist(Player $player) {
  $data = $this->table();
  $data = $data->query("SELECT name FROM profile WHERE name='" . $player->getName() . "';");
  return isset($data->fetchArray(SQLITE3_ASSOC)['name']);
 }
 
 public function getMoney(Player $player) {
  if ($this->exist($player)) {
   $data = $this->table()->query("SELECT * FROM profile WHERE name='" . $player->getName() . "';");
   return $data->fetchArray(SQLITE3_ASSOC)['money'];
  }
  return 0; 
 }
 
 public function setMoney(Player $player, Int $value) {
  if ($this->exist($player)) {
   $this->table()->query("UPDATE profile SET money='" . $value . "' WHERE name='" . $player->getName() . "';");
   return true;
  }
  return false;
 }
 
 public function addMoney(Player $player, Int $value) {
  if ($this->exist($player)) {
   $this->table()->query("UPDATE profile SET money=money+'" . $value . "' WHERE name='" . $player->getName() . "';");
   return true;
  }
  return false;
 }
 
 public function reduceMoney(Player $player, Int $value) {
  if ($this->exist($player)) {
   if ($this->getMoney($player) < $value) {
    $this->table()->query("UPDATE profile SET money=0 WHERE name='" . $player->getName() . "';");
   }else{
    $this->table()->query("UPDATE profile SET money=money-'" . $value . "' WHERE name='" . $player->getName() . "';");
   }
   return true;
  }
  return false;
 }
 
 public function getAllInOrder() : array {
  $top = "";
  $count = 0;
  $tab = array();
  $table = $this->table()->query("SELECT * FROM profile ORDER BY money DESC LIMIT 10;");
  while ($get = $table->fetchArray(SQLITE3_ASSOC)) {
   $to = $count+1;
   $tab[$count]['name'] = $get['name'];
   $tab[$count]['money'] = $get['money'];
   $name = $tab[$count]['name'];
   $money = $tab[$count]['money'];
   $top .= "{$name}:{$money}&";
   $count = $count + 1;
  }
  return explode("&", $top);
 }
 
 public function getAll() : array {
		$rows = [];
		$query = $this->table()->query("SELECT * FROM profile");
		foreach($query->fetchall(SQLITE3_ASSOC) as $data){
			$rows[$data[0]] = $data[1];
		}
		$query->fetchArray(SQLITE3_ASSOC);

		return $rows;
	}  
 
}
 