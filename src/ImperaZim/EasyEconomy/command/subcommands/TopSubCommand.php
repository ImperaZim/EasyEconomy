<?php

namespace ImperaZim\EasyEconomy\command\subcommands;

use pocketmine\Server;
use pocketmine\player\Player;
use pocketmine\command\CommandSender;

use ImperaZim\EasyEconomy\EasyEconomy;

use CortexPE\Commando\BaseSubCommand;
use CortexPE\Commando\args\RawStringArgument;

class TopSubCommand extends BaseSubCommand {

 protected function prepare() : void {
  $this->setPermission("easyeconomy.command.top");
 }

 public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void {
  $plugin = EasyEconomy::getInstance();
  $provider = EasyEconomy::getInstance()->getProvider();
  
  $list = '';
  foreach ($provider->getAllInOrder() as $hash => $data) {
    $list .= EasyEconomy::getProcessedTags(
     [
       '{id}' => $data['id'],
       '{player}' => $data['name'],
       '{money}' => $data['money']
     ],
     $plugin->getMessages()->getNested("messages.topmoney.place_preset") . "\n");
  }
    
  $sender->sendMessage(EasyEconomy::getProcessedTags(['{place_preset}' => $list],
  $plugin->getMessages()->getNested("messages.topmoney.title_preset")));
 }
}
