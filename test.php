<?php

/**
 * A simple file to run and check if pdl works.
 */

include_once 'pdl.php';

pdl(true, 'boolean');

pdl('hello', 'some string');

pdl(
    [
        'bar' => 'foo',
        [
            'sub' => [
                'sub' => 'array'
            ]
        ],
        1,
        'x',
        []
    ],
    'example array'
);

class Test
{
    public string $bar = 'foo';
    private bool $false = true;

    public function pubFun() { }

    protected function protectedFun() { }

    private function privateFun() { }

    public function pdl() {
        pdl($this);
    }
}

$class = new Test();
$class->pdl();
