<?php

namespace GlaciercreepsMC\morecommands;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\server\ServerCommandEvent;
use pocketmine\utils\TextFormat;
use \pocketmine\command\CommandSender;

class LastCommandListener implements Listener {
    
    //each player or console will store an array of 10 past commands
    //(not including "/last") as history
    private $lastCommands;
    private $plugin;
    
    private $errorMessages = [];
    
    //used to store the command sender's last (possible) error
    private $msg;
    
    
    public function __construct(PluginBase $plugin) {
        $this->plugin = $plugin;
	$plugin->getServer()->getPluginManager()->registerEvents($this, $plugin);
        
        $this->errorMessages = [
            "empty" => TextFormat::YELLOW."Command history empty!",
            "notbetween" => TextFormat::YELLOW."Invalid count. Must provide a # from 1-10",
            "invalidcount1" => TextFormat::YELLOW."Invalid. So far, you have run ",
            "invalidcount2" => TextFormat::YELLOW." commands."
        ];
        
    }
    
    public function onPlayerCommandPreprocessEvent(PlayerCommandPreprocessEvent $event){
        $id = $event->getPlayer()->getUniqueId();
        $cmd = strtolower($event->getMessage());
        
        if (!array_key_exists($id, $this->lastCommands)){
            $this->lastCommands[$id] = [];
            if (strpos($cmd, "last") !== false){
                return;
            } else {
                $this->addCommandToHistory($id, $cmd);
            }
            
        } else {
            if (strpos($cmd, "last") !== false){
                return;
            } else {
                $this->addCommandToHistory($id, $cmd);
            }
        }
        
    }
    
    public function onServerCommandEvent(ServerCommandEvent $event){
        $cmd = strtolower($event->getCommand());
        
	if (!array_key_exists($id, $this->lastCommands)){
            $this->lastCommands["server"] = [];
            if (strpos($cmd, "last") !== false){
                return;
            } else {
                $this->addCommandToHistory("server", $cmd);
            }
            
        } else {
            if (strpos($cmd, "last") !== false){
                return;
            } else {
                $this->addCommandToHistory("server", $cmd);
            }
        }
        
    }
    
    public function onPlayerQuit(PlayerQuitEvent $event){
        unset($this->lastCommands[$event->getPlayer()->getUniqueId()]); //save memory
    }
    
    //$backCount is how many commands back e.g.
    //history:
    //[3] - gma
    //[2] - say hi
    //[1] - gms
    //last 3 = gma
    public function getLastCommand(CommandSender $sender, $backCount = 1){
        
        if (!$this->hasLastCommand($sender)){
            $this->setLastCommandErrorMsg($this->errorMessages["empty"]);
            return null;
        }
        
        if ($backCount <= 0 || $backCount > 10){
            //$this(TextFormat::YELLOW."Invalid count. Must provide a # from 1-10");
            $this->setLastCommandErrorMsg($this->errorMessages["notbetween"]);
            return null;
        }
        
        $id = ($sender instanceof Player) ? $sender->getUniqueId() : "server";
        
        $count = count($this->lastCommands[$id]);
        if ($backCount > $count){
            $this->setLastCommandErrorMsg($this->errorMessages["invalidcount1"].TextFormat::AQUA.$count.$this->errorMessages["invalidcount2"]);
            return null;
        }
        
        
        //for some reason, commands on the console wont work if it starts
        //with a slash. substr() will get rid of it
        if ($backCount === 1){
            $index = $this->getLastElementIndex($this->lastCommands[$id]);
            return ($sender instanceof Player) ? (substr($this->lastCommands[$id][$index], 1)) : $this->lastCommands[$id][$index];
        } else if ($backCount >= 2) {
            $index = $count - $backCount;
            return ($sender instanceof Player) ? (substr($this->lastCommands[$id][$index], 1)) : $this->lastCommands[$id][$index];
        }
        
    }
    
    public function hasLastCommand(CommandSender $sender){
        $id = ($sender instanceof Player) ? $sender->getUniqueId() : "server";
        
        return (count($this->lastCommands[$id]) >= 1);
    }
    
    
    
    public function showHistory(CommandSender $sender){
        $id = ($sender instanceof Player) ? $sender->getUniqueId() : "server";
        $brack1 = TextFormat::GOLD."[";
        $brack2 = TextFormat::GOLD."]";
        $a = TextFormat::AQUA;
        $count = count($this->lastCommands[$id]);
        
        
        if ($count === 0){
            $sender->sendMessage($this->errorMessages["empty"]);
            return;
        }
        
        //i for counting down, j for going up the array
        for ($i = $count-1, $j = 0; $i >= 0; $i--, $j++){
            $sender->sendMessage($brack1.$a.($i+1).$brack2.": ".$a.$this->lastCommands[$id][$j]);
        }
    }

    private function addCommandToHistory($id, $cmd){
        if (count($this->lastCommands[$id]) < 10)
            array_push($this->lastCommands[$id], $cmd);
        else {
            array_shift($this->lastCommands[$id]); //get rid of 10th command
            array_push($this->lastCommands[$id], $cmd);
        }
    }
    
    private function setLastCommandErrorMsg($msg){
        $this->msg = $msg;
    }
    
    public function getLastCommandErrorMsg(){
        return $this->msg;
    }


    //safe, this isn't called unless the array's count is at least 1
    //a reference, to not send a copy. I know, it shouldnt matter much as it's just 10 elements.
    //I like to save memory though.
    private function getLastElementIndex(array &$array){
        //get key of last element with these 2 lines
        end($array);
        $index = key($array);
        //points array's internal pointer to beginning element
        reset($array);
        
        return $index;
    }
    
}


