<?php

namespace Superbolt\SuperboltBundle\EventListener;

use GuzzleHttp\Exception\GuzzleException;
use Superbolt\Core\Api;
use Superbolt\Core\Cron;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
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
        ];
    }

    public function onConsoleStart(ConsoleCommandEvent $event): void
    {
        $command = $event->getCommand();

        try {
            $response = $this->cronLogger->sendStartPing(
                $command ? $command->getName() : null,
                '**',
                $this->environment
            );
        } catch (GuzzleException $exception) {
            throw $exception;
        }

        $this->cronToken = $response->getCronToken();
    }

    public function onConsoleFinish(ConsoleTerminateEvent $event): void
    {
        if ($this->cronToken === null) {
            return;
        }

        try {
            $this->cronLogger->sendFinishPing(
                $this->cronToken,
                $event->getExitCode()
            );
        } catch (GuzzleException $exception) {
            throw $exception;
        }
    }
}
