<?php

namespace ImperaZim\EasyEconomy\Functions\DataBase\Provinder;

use pocketmine\player\Player;
use ImperaZim\EasyEconomy\Functions\DataBase\DataBase;

class MySQL implements \ImperaZim\EasyEconomy\Functions\DataBase\ProvinderBase {
 
 public function table() {
  $config = DataBase::getInstance()->getConfig()->get("database-provider", []);
		$db = new \mysqli(
			$config["host"] ?? "51.81.47.131",
			$config["user"] ?? "u720_m5HkgVNSm8",
			$config["password"] ?? "dZJBsc=3Fno@OsWpY3dB+zpp",
			$config["db"] ?? "s720_easyeconomy",
			$config["port"] ?? 3306);
 		if($db->connect_error){
 			$this->plugin->getLogger()->critical("Could not connect to MySQL server: ".$db->connect_error);
 		}
 		return $db;
 }
  
 public function createTable() {
  $this->table()->query("CREATE TABLE IF NOT EXISTS profile(name TEXT, money INT)"); 
 }
 
 public function createProfile(Player $player) {
  if (!$this->exist($player)) {
   $name = $player->getName();
   $money = DataBase::getInstance()->DEFAULT_MONEY;
   $perfil = $this->table()->prepare("INSERT INTO profile(name, money) VALUES ('" . $this->table()->real_escape_string($name) . "', $money)");
  }  
 }
 
 public function exist(Player $player) {
  $data = self::table();
  $name = $player->getName();
 	$result = $data->query("SELECT * FROM profile WHERE name = '$name'");
 	return $result->num_rows > 0 ? true : false;
 }
 
 public function getMoney(Player $player) {
  if ($this->exist($player)) {
   $res = $this->table()->query("SELECT money FROM profile WHERE name='".$this->table()->real_escape_string($player->getName())."'");
 		$ret = $res->fetch_array()[0] ?? 0;
 		$res->free();
 		return $ret; 
  }
  return 0; 
 }
 
 public function setMoney(Player $player, Int $value) {
  if ($this->exist($player)) {
   $this->table()->query("UPDATE profile SET money = $value WHERE name='".$this->db->real_escape_string($player->getName())."'"); 
   return true;
  }
  return false;
 }
 
 public function addMoney(Player $player, Int $value) {
  if ($this->exist($player)) {
   $this->table()->query("UPDATE profile SET money = money + $value WHERE name='".$this->db->real_escape_string($player->getName())."'");
   return true;
  }
  return false;
 }
 
 public function reduceMoney(Player $player, Int $value) {
  if ($this->exist($player)) {
   if ($this->getMoney($player) < $value) {
    $this->table()->query("UPDATE profile SET money = 0 WHERE name='".$this->db->real_escape_string($player->getName())."'"); 
   }else{
    $this->table()->query("UPDATE profile SET money = money - $value WHERE name='".$this->db->real_escape_string($player->getName())."'");
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
  while ($get = $table->fetch_array()) {
   $to = $count+1;
   $tab[$count]['name'] = ($get[0])->free() ?? "unknow";
   $tab[$count]['money'] = ($get[1])->free() ?? 0;
   $name = $tab[$count]['name'];
   $money = $tab[$count]['money'];
   $top .= "{$name}:{$money}&";
   $count = $count + 1;
  }
  return explode("&", $top) ;
 } 
 
 public function getAll() : array {
		$rows = [];
		$query = $this->table()->query("SELECT * FROM profile");
		foreach($query->fetch_all() as $data){
			$rows[$data[0]] = $data[1];
		}
		$query->free();

		return $rows;
	} 
 
}
 
