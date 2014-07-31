<?php

namespace GlaciercreepsMC\morecommands\manager;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\Player;
use pocketmine\command\CommandSender;

class MuteManager implements Listener {
    
    private $plugin;
    private $muted = array();
    
    public function __construct(PluginBase $plugin) {
        $this->plugin = $plugin;
        $this->plugin->getServer()->getPluginManager()->registerEvents($this, $this->plugin);
    }
    
    public function mutePlayer(Player $player, CommandSender $sender){
        if (in_array($player->getID(), $this->muted)){ //Use ID's instead of player names
            $sender->sendMessage("Player '".$player->getName()."' is already muted!");
        } else {
            array_push($this->muted, $player->getID());
            $sender->sendMessage("Player '".$player->getName()."' is now muted.");
            $player->sendMessage("You have been muted.");
        }
    }
    
    public function unmutePlayer(Player $player, CommandSender $sender){
        if (in_array($player->getID(), $this->muted)){
            $sender->sendMessage("Player '".$player->getName()."' was unmuted.");
            $player->sendMessage("You are now unmuted.");
        } else {
            $sender->sendMessage("Player '".$player->getName()."' was never muted!");
        }
    }
    
    public function onPlayerChat(PlayerChatEvent $event)
    {
        $player = $event->getPlayer();
        if (in_array($player->getID(), $this->muted)){
            $event->setCancelled();
        }
    }
    
}