<?php

namespace ImperaZim\EasyEconomy\Command\Money\Subcommands; 

use pocketmine\player\Player; 
use pocketmine\command\Command; 
use pocketmine\command\CommandSender; 
use ImperaZim\EasyEconomy\PluginUtils; 

class PayCommand {
 
 public function __construct($cmd, CommandSender $player, array $args) {
  if (!isset($args[2])) {
   $player->sendMessage(PluginUtils::convertString([], [], "{prefix} §7Use /money pay (player) (value)"));
   return;
  } 
  $plugin = $cmd->getOwningPlugin(); 
  $target = $plugin->getServer()->getPlayerExact($args[1]);
  $money = (int) $args[2];
  if (!$target instanceof Player) {
   $player->sendMessage(PluginUtils::convertString(["{player}"], [$args[1]], $plugin->getMessages()->getNested("commands.moneypay.invalid_player")));
   return;
  }
 	if (!is_numeric($money)) {
   $player->sendMessage(PluginUtils::convertString([], [], $plugin->getMessages()->getNested("commands.moneypay.invalid_number")));
   return;
  }
  $provinder = $plugin->getProvinder();
  if ($provinder->getMoney($player) < $money) {
   $player->sendMessage(PluginUtils::convertString([], [], $plugin->getMessages()->getNested("commands.moneypay.Insufficient_money")));
   return;
  }
  if ($provinder->reduceMoney($player, $money)) {
   $provinder->addMoney($target, $money);
   $money = PluginUtils::convertCurrency($money); 
   $player->sendMessage(PluginUtils::convertString(["{player}", "{money}"], [$target->getName(), $money], $plugin->getMessages()->getNested("commands.moneypay.money_successfully_sent")));
   $target->sendMessage(PluginUtils::convertString(["{player}", "{money}"], [$player->getName(), $money], $plugin->getMessages()->getNested("commands.moneypay.money_successfully_received")));
   return;
  }
  $player->sendMessage(PluginUtils::convertString([], [], "{prefix} §7Use /money pay (player) (value)")); 
 }

} 
