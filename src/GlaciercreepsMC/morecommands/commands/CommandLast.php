<?php

use GlaciercreepsMC\morecommands\base\BaseCommand;
use GlaciercreepsMC\morecommands\MoreCommands;

use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\event\server\ServerCommandEvent;
use pocketmine\utils\TextFormat;

class CommandLast extends BaseCommand implements Listener {
	
	//each player/console will store an array of 10
	//past commands, not including "last" commands
	private $lastCommands;
	
	//holds the types of error messages
	private $errorMessages;
	
	//used to store the command sender's last possible error
	private $msg;
	
	public function __construct(MoreCommands $plugin) {
		parent::__construct($plugin, "last", "Repeats last nth command", "/last or /last <1-10>", $aliases, null);
		$plugin->getServer()->getPluginManager()->registerEvents($this, $plugin);
		
		$this->lastCommands = [];
		
		$this->errorMessages = [
			"empty" => \pocketmine\utils\TextFormat::YELLOW."Command history empty!",
			"notbetween" => TextFormat::YELLOW."Invalid count. Must provide a # from 1-10",
			"toohigh1" => TextFormat::YELLOW."Too high! So far you have run ",
			"toohigh2" => TextFormat::YELLOW." commands."
		];
		
	}
	
	public function execute(CommandSender $sender, $commandLabel, array $args) {
		//Ignore perms; let the 'last' command's owning plugin handle them
		$id = ($sender instanceof Player) ? $sender->getUniqueId() : "server";

		$count = count($args);
		if ($count === 0){
			$cmd = $this->getLastCommand($id);
			if ($cmd !== null){
				$this->getPlugin()->getServer()->dispatchCommand($sender, $this->getLastCommand($id));
			} else {
				$sender->sendMessage($this->getError());
			}
			return true;
		}

		if ($count === 1){
			if ($args[0] === "history"){
				$this->showHistory($id);
				return true;
			}
			
			$backCount = (int) $args[0];
			if (!is_int($backCount)){
				return false;
			}
			
			$cmd = $this->getLastCommand($id, $backCount);
			if ($cmd !== null){
				$this->getPlugin()->getServer()->dispatchCommand($sender, $cmd);
			} else {
				$sender->sendMessage($this->getError());
			}
			return true;
		}
	}
	
	public function onPlayerCommandPreprocessEvent(PlayerCommandPreprocessEvent $event){
		if (strpos($event->getMessage(), "last") !== false) return;

		$id = $event->getPlayer()->getUniqueId();
		$cmd = $event->getMessage();
		
		if (!array_key_exists($id, $this->lastCommands)){
			$this->lastCommands[$id] = [];
			$this->addCommandToHistory($id, $cmd);
		} else {
			$this->addCommandToHistory($id, $cmd);
		}
	}

	public function onServerCommandEvent(ServerCommandEvent $event){
		if (strpos($event->getCommand(), "last") !== false) return;

		$id = "server";
		$cmd = $event->getCommand();

		if (!array_key_exists($id, $this->lastCommands)){
			$this->lastCommands[$id] = [];
			$this->addCommandToHistory($id, $cmd);
		} else {
			$this->addCommandToHistory($id, $cmd);
		}
	}
	

	/**
	 * Adds a command to the command sender's history.<br />
	 * If the history is full (has 10 commands), then
	 * the first element is removed from the history.
	 * 
	 * @param string $id     The command sender's id
	 * @param string $cmd    The command to be added
	 */
	public function addCommandToHistory($id, $cmd){
		if (count($this->lastCommands[$id]) < 10){
			array_push($this->lastCommands[$id], $cmd);
		} else {
			array_shift($this->lastCoamands[$id]);
			array_push($this->lastCommands[$id], $cmd);
		}
		
	}

	/**
	 * Shows the command history to the command sender
	 * 
	 * @param string $id     The id of the command sender
	 */
	public function showHistory($id){
		if (!$this->hasLastCommand($id)){
			$sender->sendMessage($this->errorMessages["empty"]);
			return;
		}
		
		$brack1 = TextFormat::GOLD."[";
		$brack1 = TextFormat::GOLD."]";
		$aqua = TextFormat::AQUA;
		
		$reversed = array_reverse($this->lastCommands[$id]);
		
		for ($i = count($this->lastCommands[$id])-1; $i >= 0; $i--){
			$start = $brack1.$aqua.($i+1).$brack2.$aqua;
			$end = TextFormat::WHITE.$this->lastCommands[$id][$i];
			
			$sender->sendMessage($start.": ".$end);
		}
	}
	
	/**
	 * Gets the last nth command of the command sender. Default is 1, the latest.
	 *  
	 * @param string $id     The id of the command sender
	 * @param int $backCount The amount of commands to go back through in the history
	 * 
	 * @return string|null   If the sender uses an invalid argument, or does not have
	 * a command history, then returns null.<br />Otherwise, returns the last nth command
	 * 
	 */
	public function getLastCommand($id, $backCount = 1){
		if (!$this->hasLastCommand($id)){
			$this->setError($this->errorMessages["empty"]);
			return null;
		}

		if ($backCount <= 0 || $backCount > 10){
			$this->setError($this->errorMessages["notbetween"]);
			return null;
		}

		$count = count($this->lastCommands[$id]);
		
		if ($backCount > $count){
			$error = $this->errorMessages["toohigh1"].$count.$this->errorMessages["toohigh2"];
			$this->setError($error);
			return null;
		}

		if ($backCount === 1){
			$index = $count - 1;
			return ($id !== "server") ? substr($this->lastCommands[$id][$index], 1) : $this->lastCommands[$id][$index];
		} else {
			$index = $count - $backCount;
			return ($id !== "server") ? substr($this->lastCommands[$id][$index], 1) : $this->lastCommands[$id][$index];
		}
	}


	/**
	 * Returns whether or not the command sender has a last command.
	 * 
	 * @param string $id     The id of the command sender.
	 * @return bool          Returns true if the command history is not empty.<br />Returns false otherwise.
	 */
	public function hasLastCommand($id){
		return (count($this->lastCommands[$id]) >= 1);
	}
	
	/**
	 * Gets the last possible error caused while running the command.
	 * 
	 * @return string     The last error. 
	 */
	public function getError(){
		return $this->msg;
	}
	
	/**
	 * Sets an error message.
	 * 
	 * @param string $error     The error message
	 */
	public function setError($error){
		$this->msg = $error;
	}
	
}

