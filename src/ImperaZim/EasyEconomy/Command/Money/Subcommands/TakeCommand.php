<?php

namespace ImperaZim\EasyEconomy\Command\Money\Subcommands; 

use pocketmine\player\Player; 
use pocketmine\command\Command; 
use pocketmine\command\CommandSender; 
use ImperaZim\EasyEconomy\PluginUtils; 

class TakeCommand {
 
 public function __construct(Command $cmd, CommandSender $player, array $args) {
  if (!isset($args[2])) {
   $player->sendMessage(PluginUtils::convertString([], [], "{prefix} §7Use /money take (player) (value)"));
   return;
  }
  $plugin = $cmd->getOwningPlugin();
  if (!$cmd->testPermission($player, "easyeconomy.take.use")) {
   $player->sendMessage(PluginUtils::convertString([], [], $plugin->getMessages()->getNested("commands.no-permission")));
   return;
  }
  $target = $plugin->getServer()->getPlayerExact($args[1]);
  $money = (int) $args[2];
  if (!$target instanceof Player) {
   $player->sendMessage(PluginUtils::convertString(["{player}"], [$args[1]], $plugin->getMessages()->getNested("commands.takemoney.invalid_player")));
   return;
  }
 	if (!is_numeric($money)) {
   $player->sendMessage(PluginUtils::convertString([], [], $plugin->getMessages()->getNested("commands.takemoney.invalid_number")));
   return;
  }
  if ($plugin->getProvinder()->reduceMoney($target, $money)) {
   $money = PluginUtils::convertCurrency($money);
   $player->sendMessage(PluginUtils::convertString(["{player}", "{money}"], [$target->getName(), $money], $plugin->getMessages()->getNested("commands.takemoney.successfully_reduce_money")));
   $target->sendMessage(PluginUtils::convertString(["{money}"], [$money], $plugin->getMessages()->getNested("commands.takemoney.successfully_reduced_money")));
   return;
  }
  $player->sendMessage(PluginUtils::convertString([], [], "{prefix} §7Use /money take (player) (value)"));
 }
 
}