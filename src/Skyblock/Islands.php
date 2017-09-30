<?php

namespace Skyblock;

use pocketmine\level\Position;
use pocketmine\level\Level;
use pocketmine\Player;
use Skyblock\Main;

class Islands
{
    public function setWorld($src, $dst)
    {
        $dir = opendir($src);
        @mkdir($dst);
        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($src . '/' . $file)) {
                    $this->setWorld($src . '/' . $file, $dst . '/' . $file);
                } else {
                    copy($src . '/' . $file, $dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }
    public function tpPlayerToHome(Player $player){
        $world = $this->getServer()->getLevelByName("sb[". $player->getName(). "]");
        $loc = $world->getSpawnLocation();
        $player->teleport(new Position($loc->x, $loc->y, $loc->z, $world));
    }
}
