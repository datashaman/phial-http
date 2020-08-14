<?php

declare(strict_types=1);

namespace Datashaman\Phial\Http\Listeners;

use Datashaman\Phial\Http\Events\RequestEvent;
use Pkerrigan\Xray\Trace;

class TraceBegin
{
    public function __invoke(RequestEvent $event): void
    {
        $request = $event->request;

        Trace::getInstance()
            ->setUrl((string) $request->getUri())
            ->setMethod($request->getMethod());
    }
}
