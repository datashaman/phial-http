<?php

declare(strict_types=1);

namespace App;

use Exception;

final class Handler
{
    function __invoke($event, $context = null)
    {
        $logger = $context->getLogger()->debug('Testing');

        return [
            'statusCode' => 200,
            'body' => json_encode(
                [
                    'message' => 'hello you world',
                    'functionName' => $context->getFunctionName(),
                ],
                JSON_THROW_ON_ERROR
            ),
            'headers' => [
                'Content-Type' => 'application/json',
            ],
        ];
    }
}
