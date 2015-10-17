<?php
namespace GlaciercreepsMC\morecommands\commands;
use GlaciercreepsMC\morecommands\base\BaseCommand;
use pocketmine\utils\TextFormat;
use pocketmine\Player;
class CommandHeal extends BaseCommand {
	
	private $plugin;
	
	public function __construct(MoreCommands $plugin){
		parent::__construct($plugin, "heal", "Heal yourself or other player", "/heal [player]");
		$this->setPermission("morecommands.heal");
	}
	
	public function execute(CommandSender $sender, $alias, array $args){
		if(!$this->testPermission($sender)){
			return false;
		}
		switch(count($args)){
			case 0:
				if (!($sender instanceof Player)){
					$sender->sendMessage(TextFormat::RED."Silly console! Try /heal <player>");
					return true;
				} else {
					$sender->setHealth(20);
					$sender->sendMessage(TextFormat::GREEN."You have been healed");
					return true;
				}
			case 1:
				$target = $this->plugin->getServer()->getPlayer($args[0]);
				if ($target == null){
					$sender->sendMessage(TextFormat::RED. "That player cannot be found");
					return true;
				} else {
					$target->setHealth(20);
					$target->sendMessage(TextFormat::GREEN."You were healed by ". $sender->getName());
					$sender->sendMessage(TextFormat::YELLOW."Player ". $target. " was healed");
					return true;
				}
			default:
				$sender->sendMessage($sender instanceof Player ? "Usage: /heal [player]" : "/heal <player>");
				return false; //break; is unnecessary because return won't let php reach that code
		}
		return true;
	}
	
}