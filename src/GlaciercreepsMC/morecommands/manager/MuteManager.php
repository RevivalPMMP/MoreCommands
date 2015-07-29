<?php

namespace GlaciercreepsMC\morecommands\manager;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\Player;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class MuteManager implements Listener {
    
    private $plugin;
    private $muted = [];
    
    public function __construct(PluginBase $plugin) {
        $this->plugin = $plugin;
        $this->plugin->getServer()->getPluginManager()->registerEvents($this, $this->plugin);
    }
    
    public function mutePlayer(Player $player, CommandSender $sender){
        $id = $player->getID();
        $name = $player->getName();
        if (in_array($id, $this->muted)){
            $sender->sendMessage(TextFormat::YELLOW."Player ".
                TextFormat::AQUA.$name.TextFormat::YELLOW." is already muted!");
        } else {
            $this->muted[$name] = $id;
            $sender->sendMessage(TextFormat::GREEN."Player ".
                    TextFormat::AQUA.$name.TextFormat::GREEN." has been muted.");
            $player->sendMessage(TextFormat::RED."You have been muted.");
        }
    }
    
    public function unmutePlayer(Player $player, CommandSender $sender){
        $id = $player->getID();
        $name = $player->getName();
        if (in_array($id, $this->muted)){
            $index = array_search($id, $this->muted);
            if ($index === false){
                $sender->sendMessage(TextFormat::YELLOW."Player ".
                        TextFormat::AQUA.$name.TextFormat::YELLOW." wasn't muted!");
            } else {
                unset($this->muted[$index]);
                $sender->sendMessage(TextFormat::GREEN."Player ".
                        TextFormat::AQUA.$name.TextFormat::GREEN." has been unmuted.");
                $player->sendMessage(TextFormat::GREEN."You have been unmuted.");
            }
        }
    }
    
    public function onPlayerChat(PlayerChatEvent $event)
    {
        $player = $event->getPlayer();
        foreach ($this->muted as $name => $id) {
            if ($player->getName() === $name && $player->getID() === $id){
                $event->setCancelled();
            } else {
                return;
            }
        }
    }
}