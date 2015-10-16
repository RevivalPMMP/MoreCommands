<?php
namespace GlaciercreepsMC\morecommands\commands;
use GlaciercreepsMC\morecommands\base\BaseCommand;
use pocketmine\utils\TextFormat as Color;
use pocketmine\Player;
class CommandHeal extends BaseCommand {

	public function __construct(MoreCommands $plugin){
        parent::__construct($plugin, "heal", "Heal you or someone else", "/heal [player]", null, ["null"]);
        $this->setPermission("morecommands.heal");
    }

	public function execute(CommandSender $sender, $alias, array $args){
		if(!$this->testPermission($sender)){
			$sender->sendMessage(Color::RED. "You dont have permission to heal!");
			return false;
		}
		if($sender instanceof Player){
			if(count($args) == 0){
				$sender->setHealth(20);
				$sender->sendMessage(Color::GREEN. "You have been healed!");
				return true;
			}
			if(count($args) >= 2){
				$sender->sendMessage(Color::RED. "Too many arguments!"):
				$sender->sendMessage(Color::RED. "Usage: /heal [player]");
				return false;
			}
		}
		if(count($args) == 1){
			$player = $this->getPlugin()->getServer()->getPlayer($args[1]);

			$player->setHealth(20);
			$player->sendMessage(Color::GREEN. "You have been healed by ". Color::YELLOW. $sender->getName());
			$sender->sendMessage(Color::YELLOW. $player->getName(). Color::GREEN. " has been healed!");	
			return true;
		}
	}

}