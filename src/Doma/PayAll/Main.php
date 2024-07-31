<?php

namespace Doma\PayAll;

use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\player\Player;
use onebone\economyapi\EconomyAPI;
use pocketmine\utils\TextFormat as C;

class Main extends PluginBase implements Listener {

    public function onEnable() : void {
        if(!class_exists(EconomyAPI::class)){
            $this->getLogger()->error("EconomyAPI plugin not found !");
            $this->onDisable();
            return;
        }
        $this->getLogger()->info("[ PayAll ] Plugin Enabled");
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }
    public function onCommand(CommandSender $player, Command $cmd, string $label, array $args) : bool {
        if(!$player instanceof Player) {
            $player->sendMessage("Use This Command In Game");
        }
        if($cmd->getName() == "payall") {
            if(count($args) >= 1) {
                $sendMoney = (int) $args[0];
                if(is_numeric($sendMoney)) {
                    if(0 < $sendMoney) {
                        $players = $this->getServer()->getOnlinePlayers();
                        $allMoney = count($players) * $sendMoney;
                        if($allMoney <= EconomyAPI::getInstance()->myMoney($player)) {
                            EconomyAPI::getInstance()->reduceMoney($player, $allMoney);
                            foreach($players as $p) {
                                EconomyAPI::getInstance()->addMoney($p, $sendMoney);
                            }
                            $this->getServer()->broadcastMessage(C::GREEN.C::BOLD. "{$player->getName()} Sent $sendMoney$ to all online players");
                            $player->sendMessage(C::RED. "You gave $sendMoney$ to all online players");
                        } else {
                            $player->sendMessage("You dont have enough money");
                        }
                    } else {
                        $player->sendMessage("You must enter a number. example: /payall 1000");
                    }
                } else {
                    $player->sendMessage("You must enter a number. example: /payall 1000");
                }
            }
        }
        return true;
    }

}


// Reader Is Gay :D
