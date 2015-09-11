<?php
namespace GlaciercreepsMC\morecommands;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
class MoreCommands extends PluginBase implements Listener {
	
	public function onEnable(){
		//Register events
		$pm = $this->getServer()->getPluginManager();
		$pm->registerEvents($this, $this): //TODO change
		
		//Register commands
		$this->registerCommands();
	}
	
	public function registerCommands(){
		//TODO
	}
	
}