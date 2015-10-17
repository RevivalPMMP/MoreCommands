<?php
namespace GlaciercreepsMC\morecommands\commands;
use GlaciercreepsMC\morecommands\base\BaseCommand;
use pocketmine\utils\TextFormat as Color;
use pocketmine\Player;
class Heal extends BaseCommand {
    private $plugin;
    public function __construct(MoreCommands $plugin){
        parent::__construct($plugin, "heal", "Heal yourself or other player", "/heal [player]");
        $this->setPermission("morecommands.heal");
        $this->plugin = plugin;
    }
    public function execute(CommandSender $sender, $alias, array $args){
        if(!$this->testPermission($sender)){
            return false;
        }
        switch(count($args)){
            case 0:
                if(!$sender instanceof Player){
                    $sender->sendMessage(Color::RED. "Usage: /heal <player>");
                    return false;
                }
                $sender->setHealth(20);
                $sender->sendMessage(Colot::GREEN . "You have been healed!");
                break;
            case 1:
                if(!$sender->hasPermission("morecommands.heal.other")){
                    $sender->sendMessage(Colot::RED . "You do not have permission to heal others");
                    return false;
                }
                $player = $this->plugin->getServer()->getPlayer($args[0]);
                if(!$player){
                    $sender->sendMessage(TextFormat::RED . "[Error] Player not found");
                    return false;
                }
                $player->setHealth(20);
                $sender->sendMessage(Color::GREEN . $player->getDisplayName() . " has been healed!");
                $player->sendMessage(Color::GREEN . "You have been healed!");
                break;
            default:
                $sender->sendMessage($sender instanceof Player ? "Usage: /heal [player]" : "Please use this command in-game");
                return false;
                break;
        }
        return true;
    }
}