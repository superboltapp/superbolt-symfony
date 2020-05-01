<?php

namespace Superbolt\SuperboltBundle\EventListener;

use Superbolt\Core\Api;
use Superbolt\Core\Cron;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Event\ConsoleErrorEvent;
use Symfony\Component\Console\Event\ConsoleTerminateEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class ConsoleEventsListener implements EventSubscriberInterface
{
    /** @var Cron */
    private $cronLogger;

    /** @var string */
    private $environment;

    /** @var string|null */
    private $cronToken;

    /** @var array */
    private $commands;

    public function __construct(string $environment, string $secret, ?string $endpoint, array $commands)
    {
        $this->environment = $environment;
        $this->commands = $commands;
        $this->cronLogger = new Cron(new Api($secret, $endpoint));
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ConsoleEvents::COMMAND => 'onConsoleStart',
            ConsoleEvents::TERMINATE => 'onConsoleFinish',
            ConsoleEvents::ERROR => 'onConsoleError',
        ];
    }

    public function onConsoleStart(ConsoleCommandEvent $event): void
    {
        $command = $event->getCommand();

        if (!$this->configuredCommand($command)) {
            return;
        }

        $response = $this->cronLogger->sendStartPing(
            $command->getName(),
            'manual',
            $this->environment
        );

        $this->cronToken = $response->getCronToken();
    }

    public function onConsoleFinish(ConsoleTerminateEvent $event): void
    {
        $command = $event->getCommand();

        if ($this->cronToken === null || !$this->configuredCommand($command)) {
            return;
        }

        $this->cronLogger->sendFinishPing(
            $this->cronToken,
            $event->getExitCode()
        );
    }

    public function onConsoleError(ConsoleErrorEvent $event): void
    {
        $command = $event->getCommand();

        if ($this->cronToken === null || !$this->configuredCommand($command)) {
            return;
        }

        $this->cronLogger->sendLog(
            $this->cronToken,
            $event->getError()->getMessage(),
            $event->getExitCode()
        );
    }

    private function configuredCommand(?Command $command): bool
    {
        if ($command === null) {
            return false;
        }

        return in_array(get_class($command), $this->commands, true);
    }
}
