<?php

namespace GlaciercreepsMC\morecommands;

use pocketmine\plugin\PluginBase;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\Player;
        
use GlaciercreepsMC\morecommands\manager\MuteManager;
use GlaciercreepsMC\morecommands\manager\FreezeManager;

class MoreCommands extends PluginBase {
    
    public $mutemanager;
    public $freezemanager;
    
    public function onEnable(){
        $this->mutemanager = new MuteManager($this);
        $this->freezemanager = new FreezeManager($this);
    }
    
    public function onDisable() {}
    
    public function onCommand(CommandSender $sender, Command $command, $label, array $args) {
        
        $cmd = strtolower($command->getName());
        //tip: Using $command->getName() allows use for aliases. Using $label would make aliases useless.
        
        switch ($cmd){
            
            case "gms":
                if (!($sender instanceof Player)){
                    $sender->sendMessage("Only opped players may use this command!");
                    return true;
                }
                $player = $this->getServer()->getPlayer($sender->getName());
                if ($player->isOp()){
                    $player->setGamemode(0);
                    return true;
                }
                break;
                
            case "gmc":
                if (!($sender instanceof Player)){
                    $sender->sendMessage("Only opped players may use this command!");
                    return true;
                }
                $player = $this->getServer()->getPlayer($sender->getName());
                if ($player->isOp()){
                    $player->setGamemode(1);
                    return true;
                }
                break;
                
            case "gma":
                if (!($sender instanceof Player)){
                    $sender->sendMessage("Only opped players may use this command!");
                    return true;
                }
                $player = $this->getServer()->getPlayer($sender->getName());
                if ($player->isOp()){
                    $player->setGamemode(2);
                    return true;
                }
                break;
            
            case "gmspc":
                if (!($sender instanceof Player)){
                    $sender->sendMessage("Only opped players may use this command!");
                    return true;
                }
                $player = $this->getServer()->getPlayer($sender->getName());
                if ($player->isOp()){
                    $player->setGamemode(3);
                    return true;
                }
                break;
                
            case "heal":
                if (count($args) == 0){
                    if ($sender instanceof Player){
                        if ($sender->hasPermission("morecommands.heal")){
                            $sender->setHealth(20);
                            $sender->sendMessage("You have been healed.");
                        }
                    } else {
                        $sender->sendMessage("Silly console, heal is for players!");
                        return false;
                    }
                }
                
                if (count($args) == 1){
                    $target = $this->getServer()->getPlayer($args[0]);
                    if ($target == null){
                        $sender->sendMessage("Player '".$args[0]."' was not found!");
                        return true;
                    } else {
                        $target->setHealth(20);
                        $target->sendMessage("You have been healed.");
                        $sender->sendMessage("Player '".$args[0]."' was healed.");
                    }
                }
                break;
                
            case "mute":
                if (count($args) == 0){
                    if ($sender->hasPermission("morecommands.mute")){
                        return false;
                        //if they have permission, return the usage; otherwise, say they dont have perms
                    }
                }
                
                if (count($args) == 1){
                    if ($sender->hasPermission("morecommands.mute")){
                        $target = $this->getServer()->getPlayer($args[0]);
                        if ($target == null){
                            $sender->sendMessage("Player '".$args[0]."' was not found!");
                            return true;
                        } else {
                            $this->mutemanager->mutePlayer($target, $sender);
                            return true;
                        }
                    }
                }
                break;
                
            case "unmute":
                if (count($args) == 0){
                    if ($sender->hasPermission("morecommands.unmute")){
                        return false;
                    }
                }
                
                if (count($args) == 1){
                    if ($sender->hasPermission("morecommands.unmute")){
                        $target = $this->getServer()->getPlayer($args[0]);
                        if ($target == null){
                            $sender->sendMessage("Player '".$args[0]."' was not found!");
                            return true;
                        } else {
                            $this->mutemanager->unmutePlayer($target, $sender);
                        }
                    }
                }
                break;
                
            case "freeze":
                if (count($args) == 0) {
                    if ($sender->hasPermission("morecommands.freeze")){
                        return false;
                    }
                }
                
                if (count($args) == 1){
                    if ($sender->hasPermission("morecommands.freeze")){
                        $target = $this->getServer()->getPlayer($args[0]);
                        if ($target == null){
                            $sender->sendMessage("Player '".$args[0]."' was not found!");
                            return true;
                        } else {
                            $this->freezemanager->freezePlayer($target, $sender);
                            return true;
                        }
                    }
                }
                break;
                
            case "unfreeze":
                if (count($args) == 0) {
                    if ($sender->hasPermission("morecommands.unfreeze")){
                        return false;
                    }
                }
                
                if (count($args) == 1){
                    if ($sender->hasPermission("morecommands.unfreeze")){
                        $target = $this->getServer()->getPlayer($args[0]);
                        if ($target == null){
                            $sender->sendMessage("Player '".$args[0]."' was not found!");
                            return true;
                        } else {
                            $this->freezemanager->unfreezePlayer($target, $sender);
                            return true;
                        }
                    }
                }
                break;
        }
        
        return true;
    }
    
}