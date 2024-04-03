<?php

declare(strict_types=1);

namespace Mencoreh\ConsumableCooldowns;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerItemConsumeEvent;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\item\Item;
use pocketmine\item\GoldenAppleEnchanted;
use pocketmine\item\EnderPearl;
use pocketmine\player\Player;
use pocketmine\event\player\PlayerQuitEvent;

class Main extends PluginBase implements Listener
{
    
    protected $cooldowns = [];
    protected string $cooldownMessage;
    protected int $enderPearlCooldown;
    protected int $eGappleCooldown;

    public function onEnable(): void
    {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->saveDefaultConfig();
        $this->cooldownMessage = $this->getConfig()->get('cooldown-message');
        $this->enderPearlCooldown = $this->getConfig()->get('ender-pearl-cooldown');
        $this->eGappleCooldown = $this->getConfig()->get('enchanted-gapple-cooldown');
    }

    public function onConsume(PlayerItemConsumeEvent $event): void
    {

        if ($event->isCancelled()) {
            return;
        }

        $player = $event->getPlayer();
        $item = $event->getItem();

        if (!($item instanceof GoldenAppleEnchanted)) return;
        if ($player->hasPermission("consumablecooldowns.bypass")) return;

        if ($this->isOnCooldown($player, $item)) {
            $event->cancel();
            $cooldownTime = (string) $this->getCooldown($player, $item);
            $player->sendMessage(str_replace("{TIME}", $cooldownTime, $this->cooldownMessage));
        } else {
            $this->setOnCooldown($player, $item);
        }
    }

    public function onPearl(PlayerItemUseEvent $event): void
    {

        if ($event->isCancelled()) {
            return;
        }

        $player = $event->getPlayer();
        $item = $event->getItem();

        if (!($item instanceof EnderPearl)) return;
        if ($player->hasPermission("consumablecooldowns.bypass")) return;

        if ($this->isOnCooldown($player, $item)) {
            $event->cancel();
            $cooldownTime = (string) $this->getCooldown($player, $item);
            $player->sendMessage(str_replace("{TIME}", $cooldownTime, $this->cooldownMessage));
        } else {
            $this->setOnCooldown($player, $item);
        }
    }

    public function onQuit(PlayerQuitEvent $event): void
    {
        $player = $event->getPlayer();
        if (isset($this->cooldowns[$player->getName()])) {
            unset($this->cooldowns[$player->getName()]);
        }
    }


    private function setOnCooldown(Player $player, Item $item): void
    {
        $playerName = $player->getName();
        $currentTime = time();

        if ($item instanceof GoldenAppleEnchanted) {
            $this->cooldowns[$playerName]['egapple'] = $currentTime + $this->eGappleCooldown;
        } elseif ($item instanceof EnderPearl) {
            $this->cooldowns[$playerName]['pearl'] = $currentTime + $this->enderPearlCooldown;
        }
    }

    private function isOnCooldown(Player $player, Item $item): bool
    {
        $playerName = $player->getName();

        if ($item instanceof GoldenAppleEnchanted) {
            return isset($this->cooldowns[$playerName]['egapple']) && $this->cooldowns[$playerName]['egapple'] > time();
        } elseif ($item instanceof EnderPearl) {
            return isset($this->cooldowns[$playerName]['pearl']) && $this->cooldowns[$playerName]['pearl'] > time();
        }

        return false;
    }

    private function getCooldown(Player $player, Item $item): int
    {
        $playerName = $player->getName();

        if ($item instanceof GoldenAppleEnchanted) {
            return isset($this->cooldowns[$playerName]['egapple']) ? $this->cooldowns[$playerName]['egapple'] - time() : 0;
        } elseif ($item instanceof EnderPearl) {
            return isset($this->cooldowns[$playerName]['pearl']) ? $this->cooldowns[$playerName]['pearl'] - time() : 0;
        }

        return 0;
    }
}