<?php

namespace Superbolt\SuperboltBundle\EventListener;

use Superbolt\Core\Api;
use Superbolt\Core\Cron;
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

    public function __construct(string $environment, string $secret, ?string $endpoint)
    {
        $this->environment = $environment;
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

        $response = $this->cronLogger->sendStartPing(
            $command ? $command->getName() : null,
            'manual',
            $this->environment
        );

        $this->cronToken = $response->getCronToken();
    }

    public function onConsoleFinish(ConsoleTerminateEvent $event): void
    {
        if ($this->cronToken === null) {
            return;
        }

        $this->cronLogger->sendFinishPing(
            $this->cronToken,
            $event->getExitCode()
        );
    }

    public function onConsoleError(ConsoleErrorEvent $event): void
    {
        if ($this->cronToken === null) {
            return;
        }

        $this->cronLogger->sendLog(
            $this->cronToken,
            $event->getError()->getMessage(),
            $event->getExitCode()
        );
    }
}
