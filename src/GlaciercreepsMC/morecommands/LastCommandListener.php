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
    private $lastCommands = [];
    private $plugin;
    private $errorMsg;
    
    public function __construct(PluginBase $plugin) {
        $this->plugin = $plugin;
	$plugin->getServer()->getPluginManager()->registerEvents($this, $plugin);
        $this->errorMsg = TextFormat::YELLOW."Command history empty!";
    }
    
    public function onPlayerCommandPreprocessEvent(PlayerCommandPreprocessEvent $event){
        $id = $event->getPlayer()->getUniqueId();
        $cmd = strtolower($event->getMessage());
        
        if (!isset($this->lastCommands[$id])){
            $this->lastCommands[$id] = [];
            if (strpos($cmd, "last") !== false){
                return;
            } else {
                $this->addCommandToHistory($id, $cmd);
            }
            return;
            
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
        
	if (!isset($this->lastCommands["server"])){
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
    
    //pseudocode
    //   /last [n]
    //   n meaning how many commands back; e.g.
    //history:
    // [2] /say hi
    // [1] /gmc
    // 
    // '/last 2' runs '/say hi'
    
    public function getLastCommand($id, CommandSender $sender, $backCount = 1){
        
        if (!$this->hasLastCommand($sender)){
            $sender->sendMessage($this->errorMsg);
            return null;
        }
        
        if ($backCount <= 0 || $backCount > 10){
            $sender->sendMessage(TextFormat::YELLOW."Invalid count. Must provide a # from 1-10");
            return null;
        }
        
        $count = count($this->lastCommands[$id]);
        if ($backCount > $count){
            $sender->sendMessage
                    (TextFormat::YELLOW."Invalid. So far, you have run ".TextFormat::AQUA.$count.TextFormat::YELLOW." commands.");
            return null;
        }
        
        
        if ($backCount === 1){
            $index = $this->getLastElementIndex($this->lastCommands[$id]);
            return $this->lastCommands[$id][$index];
        } else if ($backCount >= 2) {
            $index = $count - $backCount;
            return $this->lastCommands[$id][$index];
        }
        
    }
    
    public function hasLastCommand(CommandSender $sender){
        $id = ($sender instanceof Player) ? $sender->getUniqueId() : "server";
        return (count($this->lastCommands[$id]) >= 1);
    }
    
    //some pseudocode, show what's expected
    // ["/say hi", "/say hello"]
    //showHistory:
    //[1]: "/say hello"
    //[2]: "/say hi"
    public function showHistory($id, CommandSender $sender){
        $brack1 = TextFormat::GOLD."[";
        $brack2 = TextFormat::GOLD."]";
        $a = TextFormat::AQUA;
        $count = count($this->lastCommands[$id]);
        
        if ($count === 0){
            $sender->sendMessage($this->errorMsg);
            return;
        }
        
        //i for counting down, j for going up the array
        //$a = ["w", "t", "f"];
        //i = 2, j = 0
        //sendmsg(i, j
        //
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
    
    //safe, this isn't called unless the array's count is at least 1
    //a reference, to not send a copy. I know, it shouldnt matter much as it's just 10 elements.
    //I like to save memory though.
    private function getLastElementIndex(array &$array){
        //get key of last element
        end($array);
        $index = key($array);
        //points array's internal pointer to beginning element
        reset($array);
        
        return $index;
    }
    
}


