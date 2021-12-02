<?php

namespace Theslowaja\Coins;

use pocketmine\player\Player;
use pocketmine\Server;

use pocketmine\plugin\PluginBase;

use pocketmine\command\{
    Command,
    CommandSender
};
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\utils\Config;

class Main extends PluginBase implements Listener{

    public function onEnable() : void 
    {
        @mkdir($this->getDataFolder());
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->pcoins = new Config($this->getDataFolder() . "coins.yml", Config::YAML, array());
        $this->getLogger()->info("Plugin Coins Is On!!!");
    }

    public function onDisable() : void 
    {
       $this->getLogger()->info("Plugin Coins Is off!!!\nYour player coin has saved");
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool 
    {
        switch($command->getName()){
            case "coin":
                if(!$sender instanceof Player){
                    $sender->sendMessage("You Dont Have Coins Dumb!!!");
                } else {
                    $pcoins = new Config($this->getDataFolder() . "coins.yml", Config::YAML);
                    $coin = $pcoins->get($sender->getName());
                    $sender->sendMessage("you have {$coin} coins!!");
                }
            break;
            case "addcoin":
                if(isset($args[0]) && is_numeric($args[1])) {
                    $coin = new Config($this->getDataFolder()."coins.yml", Config::YAML);      
                    $n = $args[0];
                    $p = $coin->get($n);
                    $coin->set($n, $p + $args[1]);
                    $coin->save();
                    $sender->sendMessage("Sucses to add ". $args[1] ." coin to ". $args[0]);
                } else {
                    $sender->sendMessage("Eror!!!");
                }
            break;
            case "reducecoin":
                if(isset($args[0]) && is_numeric($args[1])) {
                    $coin = new Config($this->getDataFolder()."coins.yml", Config::YAML);      
                    $n = $args[0];
                    $p = $coin->get($n);
                    $coin->set($n, $p - $args[1]);
                    $coin->save();
                    $sender->sendMessage("Sucses to reduce ". $args[1] ." coin to ". $args[0]);
                } else {
                    $sender->sendMessage("Eror!!!");
                }
            break;
         }
    return true;
    }

    public function onJoin(PlayerJoinEvent $ev)
    {
        $p = $ev->getPlayer();
        $nm = $p->getName();
        $pcoins = new Config($this->getDataFolder() . "coins.yml", Config::YAML);
        if(!$pcoins->exists($nm)){
            $pcoins->set($nm, 0);
            $pcoins->save();
        }
    }
}
