<?php

namespace cosmicnebula200\CommandPermissionMessage;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;

class Main extends PluginBase implements Listener
{

    /** @var bool */
    private bool $enabled = false;

    public function onEnable(): void
    {
        $this->saveDefaultConfig();
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    /**
     * @param PlayerJoinEvent $event
     *
     * registers on PlayerJoinEvent as not all commands are registered while the plugin is enabled
     */
    public function onJoin(PlayerJoinEvent $event): void
    {
        if (!$this->enabled)
        {
            $excludedCommands = $this->getConfig()->get('excluded-commands');
            foreach($this->getServer()->getCommandMap()->getCommands() as $command)
            {
                if (in_array($command->getName(), $excludedCommands) or array_merge($excludedCommands, $command->getAliases()) !== array_diff($excludedCommands, $command->getAliases()))
                    continue;
                $command->setPermissionMessage(TextFormat::colorize(str_replace("{COMMAND}", $command->getName(), $this->getConfig()->get('permission-message'))));
            }
            $this->enabled = true;
        }
    }

}