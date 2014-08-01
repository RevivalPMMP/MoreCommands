<?php

namespace GlaciercreepsMC\morecommands\manager;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\entity\EntityMoveEvent;
use pocketmine\Player;
use pocketmine\command\CommandSender;

class FreezeManager implements Listener {
    
    public $plugin;
    public $frozen = array();
    
    public function __construct(PluginBase $plugin) {
        $this->plugin = $plugin;
        $this->plugin->getServer()->getPluginManager()->registerEvents($this, $this->plugin);
    }
    
    public function freezePlayer(Player $player, CommandSender $sender){
        $id = $player->getID();
        $name = $player->getName();
        
        if (in_array($id, $this->frozen)){
            $sender->sendMessage("Player '".$name."' is already frozen!");
        } else {
            $this->frozen[$name] = $id;
            $sender->sendMessage("Player '".$name."' is now frozen.");
            $player->sendMessage("You have been frozen.");
        }
    }
    
    public function unfreezePlayer(Player $player, CommandSender $sender){
        $id = $player->getID();
        $name = $player->getName();
        if (in_array($id, $this->frozen)){
            $index = array_search($id, $this->frozen);
            if ($index === false){
                $sender->sendMessage("Player '".$name."' wasn't frozen!");
            } else {
                unset($this->frozen[$index]);
                $sender->sendMessage("Player '".$name."' has been unfrozen.");
                $player->sendMessage("You have been unfrozen.");
            }
        }
    }
    
    public function getFrozenPlayers(){
        return $this->frozen;
    }
    
    //Did NOT realize there was an EntityMoveEvent the first time :c
    public function onEntityMove(EntityMoveEvent $event)
    {
        $entity = $event->getEntity();
        if ($entity instanceof Player){
            $player = $entity;
            foreach ($this->frozen as $name => $id){
                if ($player->getName() === $name && $player->getID() === $id){
                    $event->setCancelled();
                }
            }
        }
    }
    
}