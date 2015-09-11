<?php
namespace GlaciercreepsMC\morecommands\base;
use pocketmine\command\Command;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\utils\TextFormat;
abstract class BaseCommand extends Command implements PluginIdentifiableCommand {

	private $plugin;

	private $consoleMsg = TextFormat::RED. "Silly console, this command is for in-game!";
	private $usageMessage;

	public function __construct(MoreCommands $plugin, $name, $desc = "", $useMessage = null, $consoleUsageMessage = null, array $aliases = []){
		parent::__construct($name, $desc, $useMessage, $aliases);
		$this->plugin = $plugin;
		$this->usageMessage = $useMessage;
		$this->consoleUsageMessage = $consoleUsageMessage;
	}

	public function getPlugin(){
		return $this->plugin;
	}

	public function getUsage(){
		$this->usageMessage;
	}

}