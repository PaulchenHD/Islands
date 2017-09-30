<?php
namespace Skyblock;
use pocketmine\level\Position;
use pocketmine\level\Level;
use pocketmine\Player;
use Skyblock\Main;
class Islands
{
    public function getMain(){
        return new Main();
    }
    public function deleteIsland(Player $player){
        if($this->isIsland($player)) {
            rmdir($this->getMain()->getServer()->getDataPath(). "worlds/sb[". $player->getName()."]");
        }
    }
    public function isIsland(Player $player){
        $potentialIsland = $this->getMain()->getServer()->getDataPath(). "worlds/sb[". $player->getName()."]";
        if(is_dir($potentialIsland)) {
            rmdir($potentialIsland);
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
