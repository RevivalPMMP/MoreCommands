<?php

namespace GlaciercreepsMC\morecommands;

use pocketmine\plugin\PluginBase;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
        
use GlaciercreepsMC\morecommands\manager\MuteManager;
use GlaciercreepsMC\morecommands\manager\FreezeManager;

class MoreCommands extends PluginBase {
    
    public $mutemanager;
    public $freezemanager;
    private $permMessage = TextFormat::RED."You do not have permission for this!";
    private $consoleMsg = TextFormat::RED."Only players may use this command!";
    
    public function onEnable(){
        $this->mutemanager = new MuteManager($this);
        $this->freezemanager = new FreezeManager($this);
    }
    
    public function onCommand(CommandSender $sender, Command $command, $label, array $args) {
        
        $cmd = strtolower($command->getName());
        $count = count($args);
        //tip: Using $command->getName() allows use for aliases. Using $label would make aliases useless.
        
        switch ($cmd){
            
            case "gms":
                if (!($sender instanceof Player)){
                    $sender->sendMessage($this->consoleMsg);
                    return true;
                }
                $player = $this->getServer()->getPlayer($sender->getName());
                if ($player->hasPermission("morecommands.gms")){
                    if ($player->getGamemode() == 0){
                        $player->sendMessage(TextFormat::RED."You are already in survival mode!");
                    } else {
                        $player->setGamemode(0);
                        $player->sendMessage(TextFormat::GREEN."You are now in survival mode!");
                    }
                    return true;
                } else {
                    $player->sendMessage($this->permMessage);
                    return true;
                }
                break;
                
            case "gmc":
                if (!($sender instanceof Player)){
                    $sender->sendMessage($this->consoleMsg);
                    return true;
                }
                $player = $this->getServer()->getPlayer($sender->getName());
                if ($player->hasPermission("morecommands.gmc")){
                    if ($player->getGamemode() == 1){
                        $player->sendMessage(TextFormat::RED."You are already in creative mode!");
                    } else {
                        $player->setGamemode(1);
                        $player->sendMessage(TextFormat::GREEN."You are now in creative mode!");
                    }
                    return true;
                } else {
                    $player->sendMessage($this->permMessage);
                    return true;
                }
                break;
                
            case "gma":
                if (!($sender instanceof Player)){
                    $sender->sendMessage($this->consoleMsg);
                    return true;
                }
                $player = $this->getServer()->getPlayer($sender->getName());
                if ($player->hasPermission("morecommands.gma")){
                    if ($player->getGamemode() == 2){
                        $player->sendMessage(TextFormat::RED."You are already in adventure mode!");
                    } else {
                        $player->setGamemode(2);
                        $player->sendMessage(TextFormat::GREEN."You are now in survival mode!");
                    }
                    return true;
                } else {
                    $player->sendMessage($this->permMessage);
                    return true;
                }
                break;
            
            case "gmspc":
                if (!($sender instanceof Player)){
                    $sender->sendMessage($this->consoleMsg);
                    return true;
                }
                $player = $this->getServer()->getPlayer($sender->getName());
                if ($player->hasPermission("morecommands.gmspc")){
                    if ($player->getGamemode() == 3){
                        $player->sendMessage(TextFormat::RED."You are already in spectator mode!");
                    } else {
                        $player->setGamemode(3);
                        $player->sendMessage(TextFormat::GREEN."You are now in adventure mode!");
                    }
                    return true;
                } else {
                    $player->sendMessage($this->permMessage);
                    return true;
                }
                break;
                
            case "slay":
                if ($sender->hasPermission("morecommands.slay")){
                    if ($count == 0){
                        return false;
                    }
                    if ($count == 1){
                        $target = $this->getServer()->getPlayer($args[0]);
                        if ($target == null){
                            $sender->sendMessage(TextFormat::YELLOW."Player '"
                                    .TextFormat::BLUE.$args[0].TextFormat::YELLOW."' was not found!");
                            return true;
                        } else {
                            $target->setHealth(0);
                            $sender->sendMessage(TextFormat::YELLOW."Player '".
                                    TextFormat::BLUE.$args[0].TextFormat::YELLOW."' has been slain.");
                            return true;
                        }
                    }
                } else {
                    $sender->sendMessage($this->permMessage);
                    return true;
                }
                break;
                
            case "heal":
                if ($sender->hasPermission("morecommands.heal")){
                    if ($count == 0){
                        if (!($sender instanceof Player)){
                            $sender->sendMessage(TextFormat::RED."Silly console, /heal is for players!");
                            return true;
                        } else {
                            $sender->setHealth(20);
                            $sender->sendMessage(TextFormat::GREEN."You have been healed.");
                            return true;
                        }
                    }
                    if ($count == 1){
                        $target = $this->getServer()->getPlayer($args[0]);
                        if ($target == null){
                            $sender->sendMessage(TextFormat::YELLOW."Player '".
                                    TextFormat::BLUE.$args[0].TextFormat::YELLOW."' was not found!");
                            return true;
                        } else {
                            $target->setHealth(20);
                            $target->sendMessage(TextFormat::GREEN."You were healed.");
                            $sender->sendMessage(TextFormat::YELLOW."Player '".
                                    TextFormat::BLUE.$args[0].TextFormat::YELLOW."' was healed.");
                            return true;
                        }
                    }
                } else {
                    $sender->sendMessage($this->permMessage);
                    return true;
                }
                break;
                
            case "mute":
                if ($sender->hasPermission("morecommands.mute")){
                    if ($count == 0){
                        return false;
                    }
                    if ($count == 1){
                        $target = $this->getServer()->getPlayer($args[0]);
                        if ($target == null){
                            $sender->sendMessage(TextFormat::YELLOW."Player '".
                                TextFormat::BLUE.$args[0].TextFormat::YELLOW."' was not found!");
                            return true;
                        } else {
                            $this->mutemanager->mutePlayer($target, $sender);
                            return true;
                        }
                    }
                } else {
                    $sender->sendMessage($this->permMessage);
                    return true;
                }
                break;
                
            case "unmute":
                if ($sender->hasPermission("morecommands.unmute")){
                    if ($count == 0){
                        return false;
                    }
                    if ($count == 1){
                        $target = $this->getServer()->getPlayer($args[0]);
                        if ($target == null){
                            $sender->sendMessage(TextFormat::YELLOW."Player '".
                                TextFormat::BLUE.$args[0].TextFormat::YELLOW."' was not found!");
                            return true;
                        } else {
                            $this->mutemanager->unmutePlayer($target, $sender);
                            return true;
                        }
                    }
                } else {
                    $sender->sendMessage($this->permMessage);
                    return true;
                }
                break;
                
            case "freeze":
                if ($sender->hasPermission("morecommands.freeze")) {
                    if ($count == 0) {
                        return false;
                    }
                    if ($count == 1) {
                        $target = $this->getServer()->getPlayer($args[0]);
                        if ($target == null) {
                            $sender->sendMessage(TextFormat::YELLOW."Player '".
                                TextFormat::BLUE.$args[0].TextFormat::YELLOW."' was not found!");
                            return true;
                        } else {
                            $this->freezemanager->freezePlayer($target, $sender);
                            return true;
                        }
                    }
                } else {
                    $sender->sendMessage($this->permMessage);
                    return true;
                }
                break;
                
            case "unfreeze":
                if ($sender->hasPermission("morecommands.unfreeze")){
                    if ($count == 0){
                        return false;
                    }
                    if ($count == 1){
                        $target = $this->getServer()->getPlayer($args[0]);
                        if ($target == null){
                            $sender->sendMessage(TextFormat::YELLOW."Player '".
                                TextFormat::BLUE.$args[0].TextFormat::YELLOW."' was not found!");
                            return true;
                        } else {
                            $this->freezemanager->unfreezePlayer($target, $sender);
                            return true;
                        }
                    }
                } else {
                    $sender->sendMessage($this->permMessage);
                    return true;
                }
                break;
        }
        
        return true;
    }
    
}