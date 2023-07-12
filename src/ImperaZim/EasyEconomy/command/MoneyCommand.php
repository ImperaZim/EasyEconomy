<?php

namespace ImperaZim\EasyEconomy\command;

use CortexPE\Commando\BaseCommand;

use pocketmine\player\Player;
use pocketmine\command\CommandSender;

use ImperaZim\EasyEconomy\EasyEconomy;
use ImperaZim\EasyEconomy\command\subcommands\PaySubCommand;
use ImperaZim\EasyEconomy\command\subcommands\SeeSubCommand;
use ImperaZim\EasyEconomy\command\subcommands\SetSubCommand;
use ImperaZim\EasyEconomy\command\subcommands\GiveSubCommand;
use ImperaZim\EasyEconomy\command\subcommands\TopSubCommand;

class MoneyCommand extends BaseCommand {

 private const BASE_PERMS = "easyeconomy.command";

 protected function prepare() : void {
  $config = EasyEconomy::getInstance()->getMessages();
  $this->setPermission(self::BASE_PERMS);
  $base = [
   'pay' => [
    'trigger' => $config->getNested('commands.pay_command.trigger', 'pay'),
    'description' => $config->getNested('commands.pay_command.description', 'Send money to another player'),
    'aliases' => $config->getNested('commands.pay_command.aliases', []),
   ],
   'see' => [
    'trigger' => $config->getNested('commands.see_command.trigger', 'see'),
    'description' => $config->getNested('commands.see_command.description', 'View another player\'s money'),
    'aliases' => $config->getNested('commands.see_command.aliases', []),
   ],
   'set' => [
    'trigger' => $config->getNested('commands.set_command.trigger', 'set'),
    'description' => $config->getNested('commands.set_command.description', 'Reset a player\'s money'),
    'aliases' => $config->getNested('commands.set_command.aliases', []),
   ],
   'give' => [
    'trigger' => $config->getNested('commands.give_command.trigger', 'give'),
    'description' => $config->getNested('commands.give_command.description', 'Give money to another player'),
    'aliases' => $config->getNested('commands.give_command.aliases', []),
   ],
   'top' => [
    'trigger' => $config->getNested('commands.top_command.trigger', 'top'),
    'description' => $config->getNested('commands.top_command.description', 'See a list of the top 10 richest'),
    'aliases' => $config->getNested('commands.top_command.aliases', []),
   ]
  ];
  $this->registerSubCommand(new PaySubCommand(
   $base['pay']['trigger'],
   $base['pay']['description'],
   $base['pay']['aliases']
  ));
  $this->registerSubCommand(new SeeSubCommand(
   $base['see']['trigger'],
   $base['see']['description'],
   $base['see']['aliases']
  ));
  $this->registerSubCommand(new SetSubCommand(
   $base['set']['trigger'],
   $base['set']['description'],
   $base['set']['aliases']
  ));
  $this->registerSubCommand(new GiveSubCommand(
   $base['give']['trigger'],
   $base['give']['description'],
   $base['give']['aliases']
  ));
  $this->registerSubCommand(new GiveSubCommand(
   $base['top']['trigger'],
   $base['top']['description'],
   $base['top']['aliases']
  ));
 }

 public function getPermission() {
  return self::BASE_PERMS;
 }

 public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void {
  $plugin = EasyEconomy::getInstance();
  if (!$sender instanceof Player) {
   $sender->sendMessage(EasyEconomy::getProcessedTags([], $plugin->getMessages()->getNested("messages.default_use")));
   return;
  }
  $money = $plugin->getProvider()->getMoney($sender);
  $tags = [
   "{money}" => EasyEconomy::getProcessedValue($money, 'currency'),
   "{money_display}" => EasyEconomy::getProcessedValue($money, 'display')
  ];
  $sender->sendMessage(EasyEconomy::getProcessedTags($tags, $plugin->getMessages()->getNested("messages.mymoney.successfully")));
 }

}