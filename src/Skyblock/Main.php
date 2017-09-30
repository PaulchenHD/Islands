<?php

namespace Skyblock;

use pocketmine\block\Block;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\level\Position;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as Color;

Class Main extends PluginBase implements Listener{
    public function onEnable(){
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        if(!is_dir($this->getDataFolder())){
            @mkdir($this->getDataFolder());
        }
        if(!file_exists($this->getDataFolder()."config.yml")){
            $config = new Config($this->getDataFolder()."config.yml", Config::YAML);
            $config->save();
        }
    }
    public function onChat(PlayerChatEvent $event){ // Only a example to set the world
        if($event->getMessage() == "okay"){
            $event->getPlayer()->getLevel()->setBlock(new Vector3($event->getPlayer()->x, $event->getPlayer()->y, $event->getPlayer()->z), new Block(1));
        }
    }
    function setWorld($src,$dst) {
        $dir = opendir($src);
        @mkdir($dst);
        while(false !== ( $file = readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                if ( is_dir($src . '/' . $file) ) {
                    $this->setWorld($src . '/' . $file,$dst . '/' . $file);
                }
                else {
                    copy($src . '/' . $file,$dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }
    public function onCommand(CommandSender $sender, Command $command, $label, array $args){
        switch($command->getName()){
            case "island":
                if($sender instanceof Player){
                    if(isset($args[0])){
                        if($args[0] == "create"){
                            $this->setWorld($this->getDataFolder() . "world", $this->getServer()->getDataPath() . "worlds/sb[". $sender->getName()."]");
                            $sender->sendMessage(Color::GREEN."");
                        }
                        elseif($args[0] == "delete"){

                        }
                        elseif($args[0] == "spawn" || $args[0] == "tp" || $args[0] == "home"){ // TODO: move the world to the worlds, if the player want to leave, remove the world from worlds and set it into the data-folder
                            if(file_exists($this->getServer()->getDataPath(). "worlds/sb[". $sender->getName()."]")){
                                $this->getServer()->loadLevel("sb[". $sender->getName()."]");

                                $world = $this->getServer()->getLevelByName("sb[". $sender->getName(). "]");

                                $loc = $world->getSpawnLocation();

                                $sender->teleport(new Position($loc->x, $loc->y, $loc->z, $world));
                            }
                            else{
                                $sender->sendMessage(Color::RED."Please make sure to create an island first.");
                            }
                        }
                        elseif($args[0] == "setspawn" || $args[0] == "settp" || $args[0] == "sethome"){
                            if($sender->getLevel()->getFolderName() == "sb[". $sender->getName(). "]"){
                                $world = $this->getServer()->getLevelByName("sb[". $sender->getName(). "]");
                                $world->setSpawnLocation(new Vector3($sender->x, $sender->y, $sender->z));
                                $sender->sendMessage(Color::GREEN."");
                            }
                        }
                        elseif($args[0] == "visit"){

                        }
                    }
                }
        }
    }
}
