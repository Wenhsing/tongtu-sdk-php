<?php

namespace Wenhsing\Tongtu\Listeners;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Wenhsing\Tongtu\Events\AfterRequestEvent;
use Wenhsing\Tongtu\Events\BeforeRequestEvent;
use Wenhsing\Tongtu\Events\RequestErrorEvent;
use Wenhsing\Tongtu\Log;

class LogEventSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            AfterRequestEvent::class => 'afterRequest',
            BeforeRequestEvent::class => 'beforeRequest',
            RequestErrorEvent::class => 'requestError',
        ];
    }

    public function afterRequest(AfterRequestEvent $e)
    {
        Log::debug("After Request Info", $e->data);
    }

    public function beforeRequest(BeforeRequestEvent $e)
    {
        Log::debug("Request Data", $e->data);
    }

    public function requestError(RequestErrorEvent $e)
    {
        Log::error('Request Error', [$e->headers, $e->uri, $e->body, $e->response]);
    }
}
