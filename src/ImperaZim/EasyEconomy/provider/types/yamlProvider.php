<?php

namespace ImperaZim\EasyEconomy\provider\types;

use pocketmine\utils\Config;
use pocketmine\player\Player;
use ImperaZim\EasyEconomy\EasyEconomy;
use ImperaZim\EasyEconomy\event\PlayerMoneyUpdateEvent;

final class yamlProvider implements Provider {

  private int $money;
  private Config $table;

  const FILE = 'players.yml';

  public function __construct(EasyEconomy $plugin) {
    $this->money = $plugin->initial_money;
    $this->table = new Config($plugin->getDataFolder() . self::FILE);
  }

  public function getName() : string {
    return "yaml";
  }

  public function createTable() : void {
    if (!isset($this->getAll()['players'])) {
      $config = $this->table;
      $config->setNested('players', []);
      $config->save();
    }
  }

  public function createProfile(Player $player) : bool {
    if ($this->exist($player)) return false;
    $config = $this->table;
    $config->setNested('players.' . $player->getName(), $this->money);
    $config->save();
    return true;
  }

  public function exist(Player $player) : bool {
    return array_key_exists($player->getName(), $this->getAll()['players']);
  }

  public function getMoney(Player $player) : mixed {
    if (!$this->exist($player)) return 0;
    return $this->getAll()['players'][$player->getName()] ?? 0;
  }

  public function setMoney(Player $player, int|float $amount) : bool {
    if (!$this->exist($player)) return false;
    $config = $this->table;
    $config->setNested('players.' . $player->getName(), $amount);
    $config->save();
    $ev = new PlayerMoneyUpdateEvent($player);
    $ev->call();
    return true;
  }

  public function addMoney(Player $player, int|float $amount) : bool {
    if (!$this->exist($player)) return false;
    $money = $this->getMoney($player) + $amount;
    $config = $this->table;
    $config->setNested('players.' . $player->getName(), $money);
    $config->save();
    $ev = new PlayerMoneyUpdateEvent($player);
    $ev->call();
    return true;
  }

  public function reduceMoney(Player $player, int|float $amount) : bool {
    if (!$this->exist($player)) return false;
    $money = $this->getMoney($player) - $amount;
    $config = $this->table;
    $config->setNested('players.' . $player->getName(), $money);
    $config->save();
    $ev = new PlayerMoneyUpdateEvent($player);
    $ev->call();
    return true;
  }

  public function getAllInOrder() : array {
    $id = 1;
    $table = [];
    if (!isset($this->getAll()['players'])) return [];
    foreach ($this->getAll()['players'] as $name => $money) {
      $data = [
        'id' => $id,
        'name' => $name,
        'money' => $money,
      ];
      $table[$money + $id] = $data;
      $id++;
    }
    krsort($table);
    $list = $table;
    $table = [];
    foreach ($list as $hash => $data) {
      if ($id < 11) {
       $table[$hash] = $data;
       $id = $id + 1;
      }else{
        break;
      }
    }
    return $table;
  }

  public function getAll() : array {
    return $this->table->getAll();
  }
}