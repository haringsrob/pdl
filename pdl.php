<?php

/**
 * This file contains the function declaration for php-pdl.
 */

global $pdl;

$pdl = [
    'port' => 9337,
    'address' => '127.0.0.1',
];

if (!function_exists('pdl')) {
    function pdl($value, string $label = null, bool $withBacktrace = true): void
    {
        include_once __DIR__ . '/DataToArray.php';
        global $pdl;

        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        socket_connect($socket, $pdl['address'], $pdl['port']);

        $output = [];
        $output['time'] = (new DateTime())->format('Y-m-d H:i:s');
        $output['data'] = DataToArray::dump($value);
        $output['label'] = $label ?? $output['time'];

        if ($withBacktrace) {
            $output['backtrace'] = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 4);
        }

        $encoded = json_encode($output);

        var_dump($encoded);

        socket_write($socket, $encoded, strlen($encoded));
        socket_close($socket);
    }
}
