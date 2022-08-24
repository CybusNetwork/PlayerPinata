<?php
declare(strict_types=1);

namespace Duo\pinata;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityItemPickupEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\item\StringToItemParser;
use pocketmine\player\Player;
use function array_rand;

class EventListener implements Listener{

    public function __construct(private Main $plugin) {
        // NOOP
    }

    public function onDamage(EntityDamageEvent $event): void {
        if($event instanceof EntityDamageByEntityEvent) {
            if($this->plugin->getEventRunning()) {
                $config = $this->plugin->getConfig();
                $commonChance = (int)$config->getNested("common.chance", 10);
                $rareChance = (int)$config->getNested("rare.chance", 5);
                $legendaryChance = (int)$config->getNested("legendary.chance", 1);

                $entity = $event->getEntity();
                $damager = $event->getDamager();
                if ($entity instanceof Player && $entity->hasPermission("player.pinata")) {
                    $entity->setHealth($entity->getMaxHealth());

                    $chance = rand(1, 200);
                    if($chance <= $commonChance) {
                        $drops = $config->getNested("common.drops", ["dirt", "coal"]);
                        $chosenDrop = StringToItemParser::getInstance()->parse((string)$drops[array_rand($drops)]);
                        $entity->getWorld()->dropItem($entity->getPosition()->add(0, 1, 0), $chosenDrop);
                    } else {
                        $chance -= $commonChance;
                        if($chance <= $rareChance) {
                            $drops = $config->getNested("rare.drops", ["iron_ingot", "gold_ingot"]);
                            $chosenDrop = StringToItemParser::getInstance()->parse((string)$drops[array_rand($drops)]);
                            $entity->getWorld()->dropItem($entity->getPosition()->add(0, 1, 0), $chosenDrop);
                        } else {
                            $chance -= $rareChance;
                            if($chance <= $legendaryChance) {
                                $drops = $config->getNested("legendary.drops", ["diamond", "emerald"]);
                                $chosenDrop = StringToItemParser::getInstance()->parse((string)$drops[array_rand($drops)]);
                                $entity->getWorld()->dropItem($entity->getPosition()->add(0, 1, 0), $chosenDrop);
                            }
                        }
                    }
                } else if($damager instanceof Player && $damager->hasPermission("player.pinata")){
                    $event->cancel();
                }
            }
        }
    }

    public function onPickup(EntityItemPickupEvent $event){
        $entity = $event->getEntity();
        if($entity instanceof Player){
            if($this->plugin->getEventRunning() && $entity->hasPermission("player.pinata")){
                $event->cancel();
            }
        }
    }

    public function onDrop(PlayerDropItemEvent $event){
        $player = $event->getPlayer();
        $item = $event->getItem();
        if($this->plugin->getEventRunning()){
            if($player->hasPermission("player.pinata") || $item->getNamedTag()->getTag(Main::PINATA_BAT) !== null){
                $event->cancel();
            }
        }
    }
}