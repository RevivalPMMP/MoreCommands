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

	public function getPlugin(){
		return $this->plugin;
	}

	public function getUsage(){
		$this->usageMessage;
	}

}