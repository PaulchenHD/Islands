<?php
namespace Skyblock;
use pocketmine\level\Position;
use pocketmine\level\Level;
use pocketmine\Player;
use Skyblock\Main;
use pocketmine\Server;
class Islands
{
    public $main;
    public function __construct(Main $main){
        $this->main = $main;
    }
    public function deleteIsland(Player $player){
        if($this->isIsland($player)) {
            // TODO: Add some code.
        }
    }
    public function isIsland(Player $player){
        if(is_dir($this->main->getServer()->getDataPath(). "worlds/sb[". $player->getName()."]")) {
            return true;
        } else {
            return false;
        }
    }
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
    public function tpPlayerToHome(Player $player, Level $world){
        $loc = $world->getSpawnLocation();
        $player->teleport(new Position($loc->x, $loc->y, $loc->z, $world));
    }
}
