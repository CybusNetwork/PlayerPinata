<?php
declare(strict_types=1);

namespace Duo\pinata;

use pocketmine\plugin\PluginBase;

class Main extends PluginBase {

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
}