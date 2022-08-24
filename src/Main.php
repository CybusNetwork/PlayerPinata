<?php
declare(strict_types=1);

namespace Duo\pinata;

use pocketmine\item\Item;
use pocketmine\item\VanillaItems;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase {

    const PINATA_BAT = "PinataBat";

    private bool $running = false;

    protected function onEnable(): void {
        $this->saveDefaultConfig();
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
        $this->getServer()->getCommandMap()->register("playerpinata", new PinataCommand($this));
    }

    public function setEventRunning(bool $running): void {
        $this->running = $running;
    }

    public function getEventRunning(): bool {
        return $this->running;
    }

    public function getPinataBat(): Item{
        $stick = VanillaItems::STICK()->setCustomName("Piñata Bat");
        $stick->getNamedTag()->setShort(self::PINATA_BAT, 1);
        return $stick;
    }
}