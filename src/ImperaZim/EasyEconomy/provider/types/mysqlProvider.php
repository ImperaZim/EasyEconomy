<?php

namespace ImperaZim\EasyEconomy\provider\types;

use pocketmine\utils\Config;
use pocketmine\player\Player;
use ImperaZim\EasyEconomy\EasyEconomy;
use ImperaZim\EasyEconomy\event\PlayerMoneyUpdateEvent;

final class mysqlProvider implements Provider {

 private int $money;
 private \mysqli $table;
 
 public function __construct(EasyEconomy $plugin) {
  $config = $plugin->getConfig()->get("database-provider", []);
  $this->table = new \mysqli(
   $config["host"] ?? "0.0.0.0",
   $config["user"] ?? "root",
   $config["password"] ?? "admin",
   $config["db"] ?? "your_db",
   $config["port"] ?? 3306
  );
  $this->money = $plugin->initial_money;
 }

 
 public function getName() : string {
  return "mysql";
 }
 
 public function createTable() : void {
  $this->table->query("CREATE TABLE IF NOT EXISTS profile(name TEXT, money INT)");
 }

 public function createProfile(Player $player) : bool {
  if ($this->exist($player)) return false;
  $perfil = $this->table->prepare("INSERT INTO profile(name, money) VALUES (?, ?)");
  $perfil->execute([$player->getName(), $this->money]);
  return true;
 }

 public function exist(Player $player) : bool {
  $stmt = $this->table->prepare("SELECT * FROM profile WHERE name=?");
  $stmt->execute([$player->getName()]);
  if (!$stmt) return false;
  return $stmt->num_rows > 0;
 }

 public function getMoney(Player $player) : mixed {
  if (!$this->exist($player)) return 0;
  $res = $this->table->query("SELECT money FROM profile WHERE name='".$this->table->real_escape_string($player->getName()) ."'");
  $ret = $res->fetch_array()[0] ?? 0;
  $res->free();
  return $ret;
 }

 public function setMoney(Player $player, int|float $amount) : bool {
  if (!$this->exist($player)) return false;
  $stmt = $this->table->prepare("UPDATE profile SET money=? WHERE name=?");
  $stmt->execute([$amount, $player->getName()]);
  $ev = new PlayerMoneyUpdateEvent($player);
  $ev->call();
  return true;
 }

 public function addMoney(Player $player, int|float $amount) : bool {
  if (!$this->exist($player)) return false;
  $stmt = $this->table->prepare("UPDATE profile SET money = money + ? WHERE name=?");
  $stmt->execute([$amount, $player->getName()]);
  $ev = new PlayerMoneyUpdateEvent($player);
  $ev->call();
  return true;
 }

 public function reduceMoney(Player $player, int|float $amount) : bool {
  if (!$this->exist($player)) return false;
  $stmt = $this->table->prepare("UPDATE profile SET money = money - ? WHERE name=?");
  $stmt->execute([$amount, $player->getName()]);
  $ev = new PlayerMoneyUpdateEvent($player);
  $ev->call();
  return true;
 }

 public function getAllInOrder() : array {
  $id = 1;
  $data = [];
  $stmt = $this->table->query('SELECT * FROM profile ORDER BY money DESC LIMIT 10');
  while ($row = $stmt->fetch_all(MYSQLI_ASSOC)) {
   if ($id <= 10) {
    $data[$row['money'] + $id] = [
     'id' => $id,
     'name' => $row['name'],
     'money' => $row['money'],
    ];
   }
   $id = $id + 1;
  }
  krsort($data);
  return $data;
 }

 public function getAll() : array {
  $rows = [];
  $stmt = $this->table->query("SELECT * FROM profile");
  $result = $stmt->fetch_all(MYSQLI_ASSOC);
  foreach ($result as $data) {
   $rows[$data['name']] = $data['money'];
  }
  return $rows;
 }
}