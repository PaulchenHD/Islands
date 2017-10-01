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
use pocketmine\Server;
use Skyblock\Islands;

Class Main extends PluginBase implements Listener{
    public $invites = [];
    public $vrequest = [];

    public function onEnable(){
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
        if(!is_dir($this->getDataFolder())){
            @mkdir($this->getDataFolder());
        }
        if(!file_exists($this->getDataFolder()."config.yml")){
            $config = new Config($this->getDataFolder()."config.yml", Config::YAML);
            $config->save();
        }
    }
    public function getIslands(){
        return new Islands($this);
    }
    public function onCommand(CommandSender $sender, Command $command, $label, array $args){
        switch($command->getName()){
            case "island":
                if($sender instanceof Player){
                    if(isset($args[0])){
                        if($args[0] == "create"){
                            if(!is_dir($this->getServer()->getDataPath(). "worlds/sb[". $sender->getName()."]")){
                                $this->getIslands()->setWorld($this->getDataFolder() . "world", $this->getServer()->getDataPath() . "worlds/sb[". $sender->getName()."]");
                                $sender->sendMessage(Color::GREEN."");
                            }
                        }
                        elseif($args[0] == "delete"){
                            $this->getIslands()->deleteIsland($sender);
                        }
                        elseif($args[0] == "spawn" || $args[0] == "tp" || $args[0] == "home"){ // TODO: move the world to the worlds, if the player want to leave, remove the world from worlds and set it into the data-folder
                            if(is_dir($this->getServer()->getDataPath(). "worlds/sb[". $sender->getName()."]")){
                                $this->getServer()->loadLevel("sb[". $sender->getName()."]");
                                $this->getIslands()->tpPlayerToHome($sender, $this->getServer()->getLevelByName("sb[". $sender->getName(). "]"));
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
                        elseif($args[0] == "visit"){ // TODO: Delete the request after 30 seconds.
                            if(isset($args[1])){
                                $player = $this->getServer()->getPlayer($args[1]);
                                if($player instanceof Player){
                                    $player->sendMessage(Color::GOLD. $sender->getName(). " want to visit your island. Type /island accept ". $sender->getName(). " into the chat if you want it, too.");

                                    $this->vrequest[$player->getName()][$sender->getName()];

                                    $sender->sendMessage(Color::GREEN. "You sent a request to ". $sender->getName(). " to visit his/her island.");
                                }
                                else{
                                    $sender->sendMessage(Color::RED."Please make sure that the player is online.");
                                }
                            }
                        }
                        elseif($args[0] == "accept") {
                            if (isset($args[1])) {
                                $player = $this->getServer()->getPlayer($args[1]);
                                if ($player instanceof Player) {
                                    if(isset($this->vrequest[$sender->getName()][$player->getName()])){
                                        $player->sendMessage(Color::GOLD. "Your request to ". $sender->getName(). "'s island got accepted!");

                                        $this->getIslands()->tpPlayerToHome($player, $this->getServer()->getLevelByName("sb[". $sender->getName(). "]"));

                                        $sender->sendMessage(Color::GOLD. ""); // TODO: Add a good message.
                                    }
                                    else{
                                        $sender->sendMessage(Color::RED. ""); // TODO: Add a good message.
                                    }
                                }
                            }
                        }
                    }
                }
        }
    }
}
