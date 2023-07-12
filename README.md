# <h3 align="center">EasyEconomy v2.0.0</h3>
<div align="center">
  
![EasyEconomy](https://raw.githubusercontent.com/ImperaZim/EasyEconomy/main/coin_frame_1.gif)

</div>

<p align="center">EasyEconomy is a simple to use economy plugin that provides an economy system for your minecraft server.</p>

- - - -
## Software support
**[EasyEconomy](https://github.com/ImperaZim/EasyEconomy)** is a PHP plugin for **[PocketMine-MP](https://github.com/pmmp/PocketMine-MP )**, designed specifically for versions 5.0 and higher.  It may not work on older versions or other webserver APIs! 

## Addons
- [EasyEconomyTycoonAddon](https://github.com/ImperaZim/EasyEconomyTycoonAddon):
It is an add-on to perform certain actions when the player is richer and changed.

## For developers

### EasyEconomy API
Use [Provider.php](https://github.com/ImperaZim/EasyEconomy/blob/main/src/ImperaZim/EasyEconomy/provider/types/Provider.php) methods in your own projects!
```php 
use ImperaZim\EasyEconomy\EasyEconomy;
$api = EasyEconomy::getInstance()->getProvider();
```
 
### EasyEconomy's events
EasyEconomy provides a number of events that can be used to hook into the plugin's code. Currently, the following events can be used:
-  [PlayerMoneyUpdateEvent](https://github.com/ImperaZim/EasyEconomy/blob/main/src/ImperaZim/EasyEconomy/event/PlayerMoneyUpdateEvent.php): **Always** called when a player has their **money** amount updated.

-  [PlayerTranferMoneyEvent](https://github.com/ImperaZim/EasyEconomy/blob/main/src/ImperaZim/EasyEconomy/event/PlayerTransferMoneyEvent.php): **Always** called when a player **transfer** money to another player.
