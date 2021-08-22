<?php

declare(strict_types=1);

namespace minijaham\BowSound;

use pocketmine\plugin\PluginBase;
use pocketmine\{
	Server,
	Player
};
use pocketmine\event\{
	Listener,
	entity\EntityDamageEvent
};
use pocketmine\utils\Config;
use pocketmine\entity\Arrow;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;

class Main extends PluginBase implements Listener
{
	
	public $config;
	
	public function onEnable() : void
	{
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->saveResource("config.yml");
                $this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
	}
	
	public function onHit(EntityDamageEvent $event)
	{
		if(($damager = $event->getDamager()) instanceof Arrow && ($player = $event->getEntity()) instanceof Player)
		{
			$entity = $damager->shootingEntity;
			$sound = new PlaySoundPacket();
			$sound->soundName = $this->config->get("bow-hit-sound");
			$sound->x = $entity->getX();
			$sound->y = $entity->getY();
			$sound->z = $entity->getZ();
			$sound->volume = 1;
			$sound->pitch = 1;
			Server::getInstance()->broadcastPacket([$entity], $sound);
		}
	}
}
