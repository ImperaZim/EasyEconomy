<?php

namespace ImperaZim\EasyEconomy\Functions\DataBase;

class DataBase extends \ImperaZim\EasyEconomy\EasyEconomy {
 
 public static function getType() : String {
  return strtoupper(self::getInstance()->getConfig()->get('database-type'));
 }
 
 public static function checkType() : bool {
  if (in_array(strtoupper(self::gettype()), ["SQLITE3", "MYSQL", "YAML"])) {
   self::getInstance()->getLogger()->notice("Database loaded successfully: " . self::getType() . " type");
   self::open()->createTable();
   return true;
  }else{
   self::getInstance()->getLogger()->warning("Database loading error: Database type does not exist!");
   self::getInstance()->getServer()->getPluginManager()->disablePlugin(self::getInstance());
   return false;
  }
 }
 
 public static function open() {
  switch (self::getType()) {
   case "YAML":
    return new Provinder\YAML();
   case "MYSQL":
    return new Provinder\MySQL();
   case "SQLITE3":
    return new Provinder\SQLite3();
  }
 }
 
}