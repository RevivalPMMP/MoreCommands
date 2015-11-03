<?php
namespace GlaciercreepsMC\morecommands\base;
use GlaciercreepsMC\morecommands\MoreCommands;
use pocketmine\command\Command;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\utils\TextFormat;
abstract class BaseCommand extends Command implements PluginIdentifiableCommand {

	private $plugin;

	private $consoleMsg;
	private $usageMessage;

	public function __construct(MoreCommands $plugin, $name, $desc, $useMessage, array $aliases = [], $consoleUsageMessage = null){
		parent::__construct($name, $desc, $useMessage, $aliases);
		$this->plugin = $plugin;
		$this->usageMessage = $useMessage;
		
		//if set, use the console message sent as an argument,
		//otherwise use the default in the else clause
		if ($consoleUsageMessage !== null){
			$this->consoleMsg = $consoleUsageMessage;
		} else {
			$this->consoleMsg = TextFormat::RED."Silly console, this command is for in-game!";
		}
	}
	
	/**
	 * Gets a MoreCommands (PluginBase) object
	 * 
	 * @return MoreCommands     
	 */
	public function getPlugin(){
		return $this->plugin;
	}

	/**
	 * Gets the command usage
	 * 
	 * @return string     The usage of the current command
	 */
	public function getUsage(){
		$this->usageMessage;
	}
	
	/**
	 * Gets the error message to be sent to the console if the current command
	 * is to be used in-game.
	 * 
	 * @return string     The message to be sent to the console
	 */	
	public function getConsoleUsageMessage(){
		return $this->consoleMsg;
	}

}