<?php

namespace ImperaZim\EasyEconomy\provider;

use ImperaZim\EasyEconomy\EasyEconomy;

final class ProviderManager {

 public static function getType() : string {
  return strtoupper(EasyEconomy::getInstance()->getConfig()->get('database-type'));
 }

 public static function check_validate() : bool {
  $type = self::getType();
  $logger = EasyEconomy::getInstance()->getLogger();

  if (in_array($type, ['SQLITE3', 'MYSQL', 'YAML'])) {
   $provider = self::open();
   $provider->createTable();
   $logger->notice('Database selected: ' . $type . ' type');
   return true;
  } else {
   throw new \InvalidArgumentException('Database error: Database type does not exist!');
   return false;
  }
 }

 public static function open() {
  switch (self::getType()) {
   case 'YAML':
    return new types\yamlProvider(EasyEconomy::getInstance());
   case 'MYSQL':
    return new types\mysqlProvider(EasyEconomy::getInstance());
   case 'SQLITE3':
    return new types\sqliteProvider(EasyEconomy::getInstance());
   default:
    throw new \InvalidArgumentException('Invalid database type: ' . self::getType());
  }
 }
}
