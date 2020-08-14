<?php

if (!function_exists('abort')) {
    /**
     * @param array<string,string|array<string,string>> $headers
     */
    function abort(
        int $code,
        string $message = '',
        array $headers = [],
        ?Throwable $previous = null
    ): void {
        throw Datashaman\Phial\Http\Exceptions\HttpException::create(
            $message,
            $code,
            $previous,
            $headers
        );
    }
}
