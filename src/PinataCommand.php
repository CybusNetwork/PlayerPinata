<?php
declare(strict_types=1);

namespace Duo\pinata;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\item\VanillaItems;
use pocketmine\player\GameMode;
use pocketmine\plugin\PluginOwned;
use pocketmine\plugin\PluginOwnedTrait;

class PinataCommand extends Command implements PluginOwned {

    use PluginOwnedTrait;

    public function __construct(Main $plugin) {
        $this->owningPlugin = $plugin;
        parent::__construct("pinata", "Make players become piñatas!", "/pinata", []);
        $this->setPermission("pinata.command");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {

        if($this->owningPlugin->getEventRunning()) {
            $this->owningPlugin->setEventRunning(false);
            foreach($this->owningPlugin->getServer()->getOnlinePlayers() as $player) {
                if($player->hasPermission("player.pinata")) {
                    $player->setImmobile(false);
                    $player->setMaxHealth(20);
                    $player->setHealth($player->getMaxHealth());
                    $player->setGamemode(GameMode::SURVIVAL());
                } else {
                    if($player->getInventory()->contains($this->owningPlugin->getPinataBat())){
                        $player->getInventory()->remove($this->owningPlugin->getPinataBat());
                    }
                }
            }
        } else {
            $this->owningPlugin->setEventRunning(true);
            foreach($this->owningPlugin->getServer()->getOnlinePlayers() as $player) {
                if($player->hasPermission("player.pinata")) {
                    $player->setImmobile(true);
                    $player->setMaxHealth(100);
                    $player->setHealth($player->getMaxHealth());
                    $player->setGamemode(GameMode::ADVENTURE());
                } else {
                    $player->getInventory()->addItem($this->owningPlugin->getPinataBat());
                }
            }
        }
    }
}