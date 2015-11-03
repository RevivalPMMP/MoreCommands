<?php
namespace GlaciercreepsMC\morecommands\commands;
use GlaciercreepsMC\morecommands\MoreCommands;
use GlaciercreepsMC\morecommands\base\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat as Color;

class GamemodeAdventure extends BaseCommand {
	
	public function __construct(MoreCommands $plugin, $name, $desc = "", $useMessage = null, array $aliases = [], $consoleUsageMessage = null) {
		parent::__construct($plugin, "gma", "Changes your gamemode to adventure", "/gma", $aliases, Color::RED."Only players may use this command!");
	}

	public function execute(CommandSender $sender, $commandLabel, string $args) {
		if (!$sender instanceof Player){
			$sender->sendMessage($this->getConsoleUsageMessage());
			return false;
		} else {
			$sender->setGamemode(2);
			return true;
		}
	}
	
}
