<?php

namespace ImperaZim\EasyEconomy\Command\Money\Subcommands; 

use pocketmine\player\Player; 
use pocketmine\command\Command; 
use pocketmine\command\CommandSender; 
use ImperaZim\EasyEconomy\PluginUtils; 

class TopCommand {
 
 public function __construct(Command $cmd, CommandSender $player, array $args) {
  $id = 1;
  $message = "";
  $plugin = $cmd->getOwningPlugin();
  $provinder = $plugin->getProvinder();
  foreach ($provinder->getAllInOrder() as $data) {
   $user = explode(":", $data);
   $name = isset($user[0]) ? $user[0] : "unclassified";
   $money = isset($user[1]) ? $user[1] : "0$";
   if ($name != "") {
    $money = PluginUtils::convertCurrency($money);  
    $table = PluginUtils::convertString(["{id}", "{player}", "{money}"], [$id, $name, $money], $plugin->getMessages()->getNested("commands.topmoney.place_preset")) . "\n "; 
    $message .= $table;
    $id = $id + 1;
   }
  }
  $player->sendMessage(PluginUtils::convertString(["{place_preset}"], [$message], $plugin->getMessages()->getNested("commands.topmoney.title_preset")));
 }
} 