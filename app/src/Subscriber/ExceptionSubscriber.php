<?php

declare(strict_types=1);

namespace App\Subscriber;

use App\Exception\DomainExceptionInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\SerializerInterface;

class ExceptionSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private SerializerInterface $serializer
    ) {
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::EXCEPTION => ['onKernelException']];
    }

    /**
     * @param ExceptionEvent $event
     */
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();
        if ($exception instanceof DomainExceptionInterface) {
            $json = $this->serializer->serialize($exception, 'json', ['json_encode_options' => JsonResponse::DEFAULT_ENCODING_OPTIONS]);
            $response = new JsonResponse($json, $exception->getStatusCode(), json: true);
            $event->setResponse($response);
        }
    }
}
