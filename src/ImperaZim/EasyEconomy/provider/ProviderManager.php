<?php

namespace ImperaZim\EasyEconomy\provider;

use ImperaZim\EasyEconomy\EasyEconomy;
use ImperaZim\EasyEconomy\provider\types\Provider;
use ImperaZim\EasyEconomy\provider\types\yamlProvider;
use ImperaZim\EasyEconomy\provider\types\mysqlProvider;
use ImperaZim\EasyEconomy\provider\types\sqliteProvider;

final class ProviderManager {
  private EasyEconomy $plugin;

  public function __construct(EasyEconomy $plugin) {
    $this->plugin = $plugin;
    $this->plugin->providers = [];
    $this->register('yaml', new yamlProvider($plugin));
    $this->register('sqlite', new sqliteProvider($plugin));
    if ($this->getProvider() === "mysql") {
     $this->register('mysql', new mysqlProvider($plugin));
    }
  }

  public function register(string $name, Provider $provider): void {
    $this->plugin->providers['database'][strtolower($name)] = $provider;
  }

  public function getProvider(): string {
    $cfg = $this->plugin->getConfig();
    return strtolower($cfg->get('database-type'));
  }

  public function validate(): bool {
    $type = $this->getProvider();
    $logger = $this->plugin->getLogger();
    try {
      if (isset($this->plugin->providers['database'][$type])) {
        $provider = $this->plugin->providers['database'][$type];
        if ($provider instanceof Provider) {
          $this->open()->createTable();
          $logger->notice('Database provider selected: ' . $provider->getName());
          return true;
        } else {
          throw new \InvalidArgumentException('Database error: Provider ' . $type . ' was not registered!');
        }
      } else {
        throw new \InvalidArgumentException('Invalid database provider: ' . $type);
      }
    } catch (\InvalidArgumentException $e) {
      return false;
    }
  }

  public function open(): ?Provider {
    $type = $this->getProvider();
    if (isset($this->plugin->providers['database'][$type])) {
      return $this->plugin->providers['database'][$type];
    } else {
      throw new \InvalidArgumentException('Invalid database provider: ' . $type);
    }
  }
}