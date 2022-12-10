<?php

namespace ImperaZim\EasyEconomy\Command\Money\Subcommands; 

use pocketmine\player\Player; 
use pocketmine\command\Command; 
use pocketmine\command\CommandSender; 
use ImperaZim\EasyEconomy\PluginUtils; 

class SetCommand {
 
 public function __construct(Command $cmd, CommandSender $player, array $args) {
  if (!isset($args[2])) {
   $player->sendMessage(PluginUtils::convertString([], [], "{prefix} §7Use /money set (player) (value)"));
   return;
  }
  $plugin = $cmd->getOwningPlugin();
  if (!$cmd->testPermission($player, "easyeconomy.set.use")) {
   $player->sendMessage(PluginUtils::convertString([], [], $plugin->getMessages()->getNested("commands.no-permission")));
   return;
  }
  $target = $plugin->getServer()->getPlayerExact($args[1]);
  $money = (int) $args[2];
  if (!$target instanceof Player) {
   $player->sendMessage(PluginUtils::convertString(["{player}"], [$args[1]], $plugin->getMessages()->getNested("commands.setmoney.invalid_player")));
   return;
  }
 	if (!is_numeric($money)) {
   $money = PluginUtils::convertElevation($money);
  }
  if ($plugin->getProvinder()->setMoney($target, $money)) {
   $money = PluginUtils::convertCurrency($money);
   $player->sendMessage(PluginUtils::convertString(["{player}", "{money}"], [$target->getName(), $money], $plugin->getMessages()->getNested("commands.setmoney.successfully_set_money")));
   $target->sendMessage(PluginUtils::convertString(["{money}"], [$money], $plugin->getMessages()->getNested("commands.setmoney.successfully_reset_money")));
   return;
  }
  $player->sendMessage(PluginUtils::convertString([], [], "{prefix} §7Use /money set (player) (value)"));
 }
 
} 