<?php

declare(strict_types=1);

namespace minijaham\BowSound;

use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\event\{
	Listener,
	entity\EntityDamageEvent,
	entity\EntityDamageByEntityEvent
};
use pocketmine\utils\Config;
use pocketmine\entity\projectile\Arrow;
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
		if($event instanceof EntityDamageByEntityEvent && ($damager = $event->getDamager()) instanceof Arrow)
		{
			if ($damager->getOwningEntity() == null) return;
			$shooter = $damager->getOwningEntity();
			$sound = new PlaySoundPacket();
			$sound->soundName = $this->config->get("bow-hit-sound", "random.orb");
			$sound->x = $shooter->getX();
			$sound->y = $shooter->getY();
			$sound->z = $shooter->getZ();
			$sound->volume = 1;
			$sound->pitch = 1;
			Server::getInstance()->broadcastPacket([$shooter], $sound);
		}
	}
}
