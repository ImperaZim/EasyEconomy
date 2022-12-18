<?php

namespace ImperaZim\EasyEconomy;

class PluginUtils extends EasyEconomy {
 
 public static function convertString($tags, $processeds, $message) {
  $message = str_replace(["{prefix}"], [self::$instance->getMessages()->getNested("prefix")], $message);
  return str_replace($tags, $processeds, $message);
 } 
 
 public static function convertCurrency($value) {
  if ($value > self::getMaxCurrency()) {
   return number_format($value);
  } 
  if ($value > 999) {
   $x = round($value);
   $x_number_format = number_format($x);
   $x_array = explode(',', $x_number_format);
   $x_format = array('K', 'M', 'B', 'T', 'Q', 'QQ', 'S', 'SS', 'OC',  'N', 'D', 'UN', 'DD', 'TR', 'QT', 'QS', 'SD', 'SPD', 'OD', 'ND', 'VG', 'UVG', 'DVG', 'TVG', 'QTV', 'QNV', 'SEV', 'SPV', 'OVG', 'NVG', 'TG');
   $x_count_parts = count($x_array) - 1;
   $x_display = $x;
   $x_display = $x_array[0] . ((int) $x_array[1][0] !== 0 ? '.' . $x_array[1][0] : '');
   $x_display .= $x_format[$x_count_parts - 1];
   return $x_display;
  }
  return $value;
 }   
  
 public static function convertElevation($value) {
  $value = strtoupper($value);
  $currency = explode("E", $value);
  $value = isset($currency[1]) ? $currency[0] : $value;
  $elevation = isset($currency[1]) ? $currency[1] : 0;
  $value = is_numeric($value) ? $value : 1;
  $elevation = is_numeric($elevation) ? $elevation : 0;
  return (int) $value . str_repeat(0, $elevation);
 }
 
 public static function getMaxCurrency() {
  return 999000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000;
  //return (int) self::convertElevation("999E93");
 }
}