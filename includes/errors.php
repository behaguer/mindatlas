<?php
namespace Whoops\Example;

use Exception as BaseException;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

$run     = new Run();
$handler = new PrettyPageHandler();

$handler->setApplicationPaths([__FILE__]);

$handler->addDataTableCallback('Details', function(\Whoops\Exception\Inspector $inspector) {
    $data = array();
    $exception = $inspector->getException();
    if ($exception instanceof SomeSpecificException) {
        $data['Important exception data'] = $exception->getSomeSpecificData();
    }
    $data['Exception class'] = get_class($exception);
    $data['Exception code'] = $exception->getCode();
    return $data;
});

$run->pushHandler($handler);

// Example: tag all frames inside a function with their function name
$run->pushHandler(function ($exception, $inspector, $run) {

    $inspector->getFrames()->map(function ($frame) {

        if ($function = $frame->getFunction()) {
            $frame->addComment("This frame is within function '$function'", 'cpt-obvious');
        }

        return $frame;
    });

});

$run->register();