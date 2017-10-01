<?php

namespace SkyBlock;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\event\player\PlayerQuitEvent;

class EventListener implements Listener {
    public $main;
    public function __construct(Main $main){
        $this->main = $main;
    }
    public function onChat(PlayerChatEvent $event){
        // nothing yet   
    }
    public function onPreLogin(PlayerPreLoginEvent $event){
        $this->main->vrequest[$event->getPlayer()->getName()] = [];
    }
    public function onQuit(PlayerQuitEvent $event){
        unset($this->main->vrequest[$event->getPlayer()->getName()]);
    }
}
