<?php

namespace ImperaZim\EasyEconomy\Command;

class Commands extends \pocketmine\Server {
 
 public static function registerAll() : void {
   $commands = [
    "Money" => new Money\MoneyCommand()
   ];
   foreach ($commands as $name => $command) {
    self::getInstance()->getCommandMap()->register($name, $command);
   }
  } 
  
} 
