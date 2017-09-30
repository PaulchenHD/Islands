<?php
namespace SkyBlock;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
class EventListener extends Listener {
    private $main;
    public function __construct(Main $main){
        $this->main->getPluginManager()->registerEvents($this, $this);
    }
    public function onChat(PlayerChatEvent $event){
        // nothing yet   
    }
}
