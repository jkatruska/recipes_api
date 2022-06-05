<?php

declare(strict_types=1);

namespace App\Subscriber;

use App\Exception\RequestParseException;
use App\Request\RequestParserFactory;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class RequestParserSubscriber implements EventSubscriberInterface
{
    /**
     * @return string[][]
     */
    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::CONTROLLER => ['onKernelController']];
    }

    /**
     * @param ControllerEvent $controllerEvent
     * @throws RequestParseException
     */
    public function onKernelController(ControllerEvent $controllerEvent): void
    {
        $request = $controllerEvent->getRequest();
        $parser = RequestParserFactory::getParser($request);
        $parser?->parse($request);
    }
}
