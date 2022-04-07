<?php

namespace cosmicnebula200\CommandPermissionMessage;

use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\ClosureTask;
use pocketmine\utils\TextFormat;

class Main extends PluginBase
{

    /** @var bool */
    private bool $enabled = false;

    public function onEnable(): void
    {
        $this->saveDefaultConfig();
        $this->getScheduler()->scheduleTask(new ClosureTask(function (): void {
            $this->registerMessages();
        }));
    }

    /**
     * @return void
     */
    public function registerMessages(): void
    {
        $excludedCommands = array_fill_keys($this->getConfig()->get('excluded-commands'), true);
        $permissionMessage = $this->getConfig()->get('permission-message');
        foreach($this->getServer()->getCommandMap()->getCommands() as $command)
        {
            if (isset($excludedCommands[$command->getName()]))
                continue;
            foreach($command->getAliases() as $alias){
                if(isset($excludedCommands[$alias]))
                    continue 2;
            }
            $command->setPermissionMessage(TextFormat::colorize(str_replace("{COMMAND}", $command->getName(), $permissionMessage)));
        }
    }

}