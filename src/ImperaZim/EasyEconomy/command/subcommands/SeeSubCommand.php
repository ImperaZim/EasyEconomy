<?php

namespace ImperaZim\EasyEconomy\command\subcommands;

use pocketmine\Server;
use pocketmine\player\Player;
use pocketmine\command\CommandSender;

use ImperaZim\EasyEconomy\EasyEconomy;

use CortexPE\Commando\BaseSubCommand;
use CortexPE\Commando\args\RawStringArgument;

class SeeSubCommand extends BaseSubCommand {

 protected function prepare() : void {
  $this->setPermission("easyeconomy.command.see");
  $this->registerArgument(0, new RawStringArgument("player"));
 }

 public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void {
  $plugin = EasyEconomy::getInstance();
  $provider = EasyEconomy::getInstance()->getProvider();
  
  $target = Server::getInstance()->getPlayerByPrefix($args["player"]);
  if (!$target instanceof Player) {
   $sender->sendMessage(EasyEconomy::getProcessedTags(['{player}' => $args["player"]], $plugin->getMessages()->getNested("messages.invalid_player")));
   return;
  }

  $amount = $provider->getMoney($target);
  
  $tags = [
   "{player}" => $target->getName(),
   "{money}" => EasyEconomy::getProcessedValue($amount, 'currency'),
   "{money_display}" => EasyEconomy::getProcessedValue($amount, 'display')
  ];
  
  $sender->sendMessage(EasyEconomy::getProcessedTags($tags, $plugin->getMessages()->getNested("messages.seemoney.successfully")));
 }
}
