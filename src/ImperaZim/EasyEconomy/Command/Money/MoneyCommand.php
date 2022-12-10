<?php

namespace ImperaZim\EasyEconomy\Command\Money;

use pocketmine\Server;
use pocketmine\player\Player;
use pocketmine\command\Command;
use pocketmine\plugin\PluginOwned;
use pocketmine\command\CommandSender;
use ImperaZim\EasyEconomy\PluginUtils;
use ImperaZim\EasyEconomy\EasyEconomy;
use ImperaZim\EasyEconomy\Functions\DataBase\DataBase; 

class MoneyCommand extends Command implements PluginOwned {

 public function __construct() {
  parent::__construct("money", "§7money command!", null, []);
 }

 public function execute(CommandSender $player, String $commandLabel, array $args) : bool {
  if (isset($args[0])) {
   if (in_array(strtolower($args[0]), ["give"])) {
    new Subcommands\GiveCommand($this, $player, $args);
    return true;
   }
   if (in_array(strtolower($args[0]), ["set"])) {
    new Subcommands\SetCommand($this, $player, $args);
    return true;
   }
   if (in_array(strtolower($args[0]), ["take"])) {
    new Subcommands\TakeCommand($this, $player, $args);
    return true;
   }
   if (in_array(strtolower($args[0]), ["top"])) {
    new Subcommands\TopCommand($this, $player, $args);
    return true;
   }
   if (in_array(strtolower($args[0]), ["help"])) {
    $message = "{prefix} §7 Command List:";
    $message .= "\n §e-> §7/money (optional = playername)";
    $message .= "\n §e-> §7/money top";
    $message .= "\n §e-> §7/money pay (player) (value) ";
    if ($this->testPermission($player, "easyeconomy.give.use")) $message .= "\n §e-> §7/money give (player) (value)";
    if ($this->testPermission($player, "easyeconomy.set.use")) $message .= "\n §e-> §7/money set (player) (value)";
    if ($this->testPermission($player, "easyeconomy.take.use")) $message .= "\n §e-> §7/money take (player) (value)";
    $player->sendMessage(PluginUtils::convertString([], [], $message)); 
    return true;
   }
   $target = Server::getInstance()->getPlayerExact($args[0]);
   if (!$target instanceof Player) {
    $player->sendMessage(PluginUtils::convertString(["{player}"], [$args[0]], EasyEconomy::getInstance()->getMessages()->getNested("commands.seemoney.invalid_player")));
    return true;
   }else{
    $money_currency = number_format(DataBase::open()->getMoney($target), 0, "", ".");
    $money = PluginUtils::convertCurrency(DataBase::open()->getMoney($target));
    $money = "{$money_currency} ({$money})";
    $player->sendMessage(PluginUtils::convertString(["{player}", "{money}"], [$target->getName(), $money], EasyEconomy::getInstance()->getMessages()->getNested("commands.seemoney.successfully")));
    return true;
   }
  }
  if (!$player instanceof Player) {
   Server::getInstance()->getLogger()->error("This command can only be used in the game"); 
   return true;
  }
  if (isset($args[0])) {
   if (in_array(strtolower($args[0]), ["pay"])) {
    new Subcommands\PayCommand($this, $player, $args);
    return true;
   }
   $target = Server::getInstance()->getPlayerExact($args[0]);
   if (!$target instanceof Player) {
    $player->sendMessage(PluginUtils::convertString(["{player}"], [$args[0]], EasyEconomy::getInstance()->getMessages()->getNested("commands.seemoney.invalid_player")));
    return true;
   }
   $money_currency = number_format(DataBase::open()->getMoney($target), 0, "", ".");
   $money = PluginUtils::convertCurrency(DataBase::open()->getMoney($target));
   $money = "{$money_currency} ({$money})";
   $player->sendMessage(PluginUtils::convertString(["{player}", "{money}"], [$target->getName(), $money], EasyEconomy::getInstance()->getMessages()->getNested("commands.seemoney.successfully")));
   return true;
  }
  $money_currency = number_format(DataBase::open()->getMoney($player), 0, "", ".");
  $money = PluginUtils::convertCurrency(DataBase::open()->getMoney($player));
  $money = "{$money_currency} ({$money})";
  $player->sendMessage(PluginUtils::convertString(["{money}"], [$money], EasyEconomy::getInstance()->getMessages()->getNested("commands.mymoney.successfully")));
  return true;
 }

 public function getOwningPlugin() : EasyEconomy {
  return EasyEconomy::getInstance();
 }

}
