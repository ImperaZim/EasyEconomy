<?php

namespace ImperaZim\EasyEconomy\provider\types;

use pocketmine\utils\Config;
use pocketmine\player\Player;
use ImperaZim\EasyEconomy\EasyEconomy;
use ImperaZim\EasyEconomy\event\PlayerMoneyUpdateEvent;

final class sqliteProvider implements Provider {

 private int $money;
 private \SQLite3 $table;

 const FILE = 'players.db';

 public function __construct(EasyEconomy $plugin) {
  $this->money = $plugin->initial_money;
  $this->table = new \SQLite3($plugin->directory . self::FILE);
 }

 public function createTable() : void {
  $this->table->exec('CREATE TABLE IF NOT EXISTS profile(name TEXT, money INT)');
 }

 public function createProfile(Player $player) : bool {
  if ($this->exist($player)) return false;
  $perfil = $this->table->prepare('INSERT INTO profile(name, money) VALUES (:name, :money)');
  $perfil->bindValue(':name', $player->getName());
  $perfil->bindValue(':money', $this->money);
  $perfil->execute();
  return true;
 }

 public function exist(Player $player) : bool {
  $data = $this->table;
  $data = $data->query("SELECT name FROM profile WHERE name='" . $player->getName() . "';");
  return isset($data->fetchArray(SQLITE3_ASSOC)['name']);
 }

 public function getMoney(Player $player) : mixed {
  if (!$this->exist($player)) return 0;
  $data = $this->table->query("SELECT money FROM profile WHERE name='" . $player->getName() . "';");
  return $data->fetchArray(SQLITE3_ASSOC)['money'];
 }

 public function setMoney(Player $player, int|float $amount) : bool {
  if (!$this->exist($player)) return false;
  $stmt = $this->table->prepare("UPDATE profile SET money=:amount WHERE name=:name");
  $stmt->bindValue(':amount', $amount);
  $stmt->bindValue(':name', $player->getName());
  $stmt->execute();
  $ev = new PlayerMoneyUpdateEvent($player);
  $ev->call();
  return true;
 }

 public function addMoney(Player $player, int|float $amount) : bool {
  if (!$this->exist($player)) return false;
  $stmt = $this->table->prepare("UPDATE profile SET money=money+:amount WHERE name=:name");
  $stmt->bindValue(':amount', $amount);
  $stmt->bindValue(':name', $player->getName());
  $stmt->execute();
  $ev = new PlayerMoneyUpdateEvent($player);
  $ev->call();
  return true;
 }

 public function reduceMoney(Player $player, int|float $amount) : bool {
  if (!$this->exist($player)) return false;
  $stmt = $this->table->prepare("UPDATE profile SET money=money-:amount WHERE name=:name");
  $stmt->bindValue(':amount', $amount);
  $stmt->bindValue(':name', $player->getName());
  $stmt->execute();
  $ev = new PlayerMoneyUpdateEvent($player);
  $ev->call();
  return true;
 }

 public function getAllInOrder() : array {
  $id = 1;
  $table = [];
  $result = $this->table->query('SELECT * FROM profile ORDER BY money DESC LIMIT 10;');
  while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
   if ($id <= 10) {
    $data = [
     'id' => $id,
     'name' => $row['name'],
     'money' => $row['money'],
    ];
    $table[$row['money'] + $id] = $data;
   }
   $id = $id + 1;
  }
  krsort($table);
  return $table;
 }

 public function getAll() : array {
  $rows = [];
  $query = $this->table->query("SELECT name, money FROM profile");
  while ($data = $query->fetchArray(SQLITE3_ASSOC)) {
   $rows[$data['name']] = $data['money'];
  }
  return $rows;
 }

}