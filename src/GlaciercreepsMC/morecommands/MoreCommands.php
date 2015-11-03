<?php

namespace GlaciercreepsMC\morecommands;

use GlaciercreepsMC\morecommands\commands\CommandHeal;
use GlaciercreepsMC\morecommands\commands\CommandLast;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\permission\Permission;

class MoreCommands extends PluginBase implements Listener {

	public function onEnable(){
		//Register events
		$pm = $this->getServer()->getPluginManager();
		$pm->registerEvents($this, $this); //TODO change
		
		//Register commands
		$this->registerCommands();
	}
	
	public function registerCommands(){
		$cm = $this->getServer()->getCommandMap();
		$cm->register("morecommands", new CommandHeal($this));
		$cm->register("morecommands", new CommandLast($this));
	}

	public function registerPermissions(){
		$pm = $this->getServer()->getPluginManager();

		$perm = new Permission("morecommands.heal", "Lets you use /heal", "op");
		$pm->addPermission($perm);
		
		//last not needed, remember, last runs a different command, so that command is handled by whatever plugin
		
		//TODO rest of permissions after commands are done
	}
	
}