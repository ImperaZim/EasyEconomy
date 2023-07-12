# EE TycoonAddon
EasyEconomyTycoonAddon is an add-on to perform certain actions when the richest
player on the list is changed to
the [EasyEconomy](https://github.com/ImperaZim/EasyEconomy) plug-in for the
Minecraft server software: [PocketMine-MP](https://github.com/pmmp/PocketMine-MP
).

## Features
Because the EasyEconomy plugin provides a detailed list of the richest players
in order, the addon can identify updates in the list and send an update event
where it updates the nametag of the players and adds an extra tag to the richest
player!

## For developers
### EasyEconomyTycoonAddon's events
EasyEconomyTycoonAddon provides a number of events that can be used to hook into the plugin's code. Currently, the following events can be used:
-  [TycoonUpdateEvent](https://github.com/ImperaZim/EasyEconomyTycoonAddon/blob/main/src/ImperaZim/EasyEconomy/event/TycoonUpdateEvent.php):
**Always** called when the **richest** player on the server is changed