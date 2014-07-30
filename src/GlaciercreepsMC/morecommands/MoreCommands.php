<?php

namespace GlaciercreepsMC\morecommands;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\Player;

class MoreCommands extends PluginBase implements Listener {
    
    private $frozen = array();
    private $unfrozen = array();
    
    public function onEnable(){
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
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
                
            case "freeze":
                //If the sender has permission, it'll return the usage; otherwise the server will say you don't have permission.
                if (count($args) == 0){
                    if ($sender->hasPermission("morecommands.freeze")){
                        return false; //returns usage
                    }
                }
                
                if (count($args) == 1){
                    if ($sender->hasPermission("morecommands.freeze")){
                        if ($args[0] == "me"){
                            if (!($sender instanceof Player)){
                                $sender->sendMessage("Only opped players can freeze themselves!");
                                return true;
                            }
                            
                            $this->freezePlayer($sender->getPlayer(), $sender);
                        } else {
                            $target = $this->getServer()->getPlayer($args[0]);
                            $this->freezePlayer($target, $sender);
                            return true;
                        }
                    }
                }
                break;
                
            case "unfreeze":
                //same here
                if (count($args) == 0){
                    if ($sender->hasPermission("morecommands.unfreeze")){
                        return false;
                    }
                }
                
                if (count($args) == 1){
                    if ($sender->hasPermission("morecommands.unfreeze")){
                        if ($args[0] == "me"){
                            if (!($sender instanceof Player)){
                                $sender->sendMessage("Only opped players can unfreeze themselves!");
                                return true;
                            }
                            $this->unfreezePlayer($sender->getPlayer(), $sender);
                            
                        } else {
                            $target = $this->getServer()->getPlayer($args[0]);
                            $this->unfreezePlayer($target, $sender);
                        }
                    }
                }
                break;
        }
        
        return true;
    }
    
    /**
     * 
     * @param \pocketmine\Player $player
     * @param \pocketmine\command\CommandSender $sender
     * Freezes the specified player, if not null
     */
    public function freezePlayer(Player $player, CommandSender $sender){
        if ($sender->hasPermission("morecommands.freeze")){
            
            if ($player == null){
                $sender->sendMessage("Player '".$player."' was not found!");
                return; //Stops code from runnning
            }
            
            if (in_array($player, $this->frozen)){
                $sender->sendMessage("Player '".$player->getName()."' is already frozen! Use /unfreeze <player>");
            } else {
                array_push($this->frozen, $player);
                if ($player->getName() == $sender->getName()){
                    $sender->sendMessage("You are now frozen. Use /unfreeze me to unfreeze yourself.");
                } else {
                    $sender->sendMessage("Player '".$player->getName()."' is now frozen.");
                    $player->sendMessage("You were frozen by a moderator!");
                }
            }
        }
    }
    
    /**
     * 
     * @param \pocketmine\Player $player
     * @param \pocketmine\command\CommandSender $sender
     * Unfreezes the specified player, if not null
     */
    public function unfreezePlayer(Player $player, CommandSender $sender){
        if ($sender->hasPermission("morecommands.unfreeze")){
            
            if ($player == null){
                $sender->sendMessage("Player '".$player->getName()."' was not found!");
            }
            
            if (in_array($player, $this->unfrozen)){
                $sender->sendMessage("Player '".$player."' wasn't frozen!");
            } else {
                array_push($this->unfrozen, $player);
                if ($player->getName() == $sender->getName()){
                    $sender->sendMessage("You are now unfrozen. Use /freeze me to freeze yourself.");
                } else {
                    $sender->sendMessage("Player '".$player->getName()."' is now frozen.");
                    $player->sendMessage("You are now unfrozen.");
                }
            }
        }
    }
    
}