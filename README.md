# EasyEconomy
An easy to use and understand economy plugin for minecraft bedrock edition servers!
- - - -
## Compatibility 
This plugin is meant to be used on servers made only in the software **[PocketMine-MP](https://github.com/pmmp/PocketMine-MP)**, it may not work perfectly in variants of it.

## Commands:
- /money ``optional [args...] ``

## EasyEconomy API
```php 
use ImperaZim\EasyEconomy\EasyEconomy;
$api = EasyEconomy::getInstance()->getProvinder();
$api->getMoney($player); // return player balance
```
 
