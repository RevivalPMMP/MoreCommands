<?php

namespace GlaciercreepsMC\morecommands\manager;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;

class FreezeManager implements Listener {
    
    public $plugin;
    public $frozen = array();
    public $unfrozen = array();
    
    public function __construct(PluginBase $plugin) {
        $this->plugin = $plugin;
    }
    
    public function freezePlayer(Player $player, CommandSender $sender){
        
    }
    
    public function getFrozenPlayers(){
        return $this->frozen;
    }
    
    /* public function onPlayerMove(PlayerMoveEvent $event){
     *  
     * }
     */
}