<?php

namespace ImperaZim\EasyEconomy;

use pocketmine\utils\Config;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;
use pocketmine\event\player\PlayerJoinEvent;

use CortexPE\Commando\PacketHooker;
use CortexPE\Commando\exception\HookAlreadyRegistered;

use ImperaZim\EasyEconomy\command\MoneyCommand;
use ImperaZim\EasyEconomy\provider\ProviderManager;

final class EasyEconomy extends PluginBase implements Listener {
 use SingletonTrait;

 public ?int $initial_money = 0;
 public ?string $directory = null;

 public function onEnable() : void {
  EasyEconomy::setInstance($this);
  if (ProviderManager::check_validate()) {
   $this->saveResource('messages.yml');
   $this->registerCommands();
   $this->registerPacketHooker();
   $this->getServer()->getPluginManager()->registerEvents($this, $this);
  }
  $this->initial_money = 1000;
  $this->directory = $this->getDataFolder();
 }

 public function getMessages() : Config {
  return new Config($this->getDataFolder() . 'messages.yml');
 }

 private function registerCommands() : void {
  $config = $this->getMessages();
  $trigger = $config->getNested('commands.money_command.trigger', 'money');
  $description = $config->getNested('commands.money_command.description', 'See your money');
  $aliases = $config->getNested('commands.money_command.aliases', []);
  $this->getServer()->getCommandMap()->register('EasyEconomy', new MoneyCommand($this, $trigger, $description, $aliases));
 }

 private function registerPacketHooker() : void {
  try {
   PacketHooker::register($this);
  } catch (HookAlreadyRegistered $exception) {
   // You don't need to do anything in this case.
  }
 }

 public static function getProcessedTags(array $tags, string $message) : string {
  $tags['{prefix}'] = self::getInstance()->getMessages()->getNested('prefix');
  return str_replace(array_keys($tags), array_values($tags), $message);
 }

 public static function getProcessedValue(int $value, string $type) : string {
  if ($type === 'display') {
   if ($value > 999) {
    $x = round($value);
    $x_number_format = number_format($x);
    $x_array = explode(',', $x_number_format);
    $x_count_parts = count($x_array) - 1;
    $x_display = $x_array[0] . ((int) $x_array[1][0] !== 0 ? '.' . $x_array[1][0] : '');
    $x_format = array('K', 'M', 'B', 'T', 'Q', 'QQ');
    $x_display .= $x_format[$x_count_parts - 1];
    return $x_display;
   }
   return (string) $value;
  }
  if ($type === 'currency') {
   return (string) number_format($value, 0, '', '.');
  }
  return '';
 }

 public function onJoin(PlayerJoinEvent $event) : void {
  $this->getProvider()->createProfile($event->getPlayer());
 }

 /* API FUNCTIONS */

 public function getProvider() {
  return ProviderManager::open();
 }
}