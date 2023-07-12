<?php

namespace ImperaZim\EasyEconomy\command\subcommands;

use pocketmine\Server;
use pocketmine\player\Player;
use pocketmine\command\CommandSender;

use ImperaZim\EasyEconomy\EasyEconomy;

use CortexPE\Commando\BaseSubCommand;
use CortexPE\Commando\args\IntegerArgument;
use CortexPE\Commando\args\RawStringArgument;

class SetSubCommand extends BaseSubCommand {

 protected function prepare() : void {
  $this->setPermission("easyeconomy.command.set");
  $this->registerArgument(0, new RawStringArgument("player"));
  $this->registerArgument(1, new IntegerArgument("amount"));
 }

 public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void {
  $amount = $args["amount"];
  $plugin = EasyEconomy::getInstance();
  $provider = EasyEconomy::getInstance()->getProvider();
  
  if ($amount <= 0) {
   $sender->sendMessage(EasyEconomy::getProcessedTags([], $plugin->getMessages()->getNested("messages.invalid_number")));
   return;
  }

  $target = Server::getInstance()->getPlayerByPrefix($args["player"]);
  if (!$target instanceof Player) {
   $sender->sendMessage(EasyEconomy::getProcessedTags(['{player}' => $args["player"]], $plugin->getMessages()->getNested("messages.invalid_player")));
   return;
  }
  
  $tags = [
   "{target}" => $target->getName(),
   "{player}" => $sender->getName(),
   "{money}" => EasyEconomy::getProcessedValue($amount, 'currency'),
   "{money_display}" => EasyEconomy::getProcessedValue($amount, 'display')
  ];
  
  $provider->setMoney($target, $amount);
  
  $sender->sendMessage(EasyEconomy::getProcessedTags($tags, $plugin->getMessages()->getNested("messages.setmoney.successfully_set_money")));
  $target->sendMessage(EasyEconomy::getProcessedTags($tags, $plugin->getMessages()->getNested("messages.setmoney.successfully_res_money")));
 }
}
