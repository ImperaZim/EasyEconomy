<?php

namespace ImperaZim\EasyEconomy\Command\Money\Subcommands; 

use pocketmine\player\Player; 
use pocketmine\command\Command; 
use pocketmine\command\CommandSender; 
use ImperaZim\EasyEconomy\PluginUtils; 

class GiveCommand {
 
 public function __construct(Command $cmd, CommandSender $player, array $args) {
  if (!isset($args[2])) {
   $player->sendMessage(PluginUtils::convertString([], [], "{prefix} §7Use /money give (player) (value)"));
   return;
  }
  $plugin = $cmd->getOwningPlugin();
  if (!$cmd->testPermission($player, "easyeconomy.give.use")) {
   $player->sendMessage(PluginUtils::convertString([], [], $plugin->getMessages()->getNested("commands.no-permission")));
   return;
  }
  $target = $plugin->getServer()->getPlayerExact($args[1]);
  $money = (int) $args[2];
  if (!$target instanceof Player) {
   $player->sendMessage(PluginUtils::convertString(["{player}"], [$args[1]], $plugin->getMessages()->getNested("commands.givemoney.invalid_player")));
   return;
  }
 	if (!is_numeric($money)) {
   $money = PluginUtils::convertElevation($money);
  }
  if ($plugin->getProvinder()->addMoney($target, $money)) {
   $money = PluginUtils::convertCurrency($money);
   $player->sendMessage(PluginUtils::convertString(["{player}", "{money}"], [$target->getName(), $money], $plugin->getMessages()->getNested("commands.givemoney.successfully_gave_money")));
   $target->sendMessage(PluginUtils::convertString(["{money}"], [$money], $plugin->getMessages()->getNested("commands.givemoney.successfully_given_money")));
   return;
  }
  $player->sendMessage(PluginUtils::convertString([], [], "{prefix} §7Use /money give (player) (value)"));
 }
 
}
