<?php

namespace App;

use Symfony\Component\ErrorHandler\ErrorRenderer\ErrorRendererInterface;
use Symfony\Component\ErrorHandler\Exception\FlattenException;

class ErrorHandler implements ErrorRendererInterface
{
    public function render(\Throwable $exception): FlattenException
    {
        $message = 'Une erreur est survenue : ' . $exception->getMessage();
        return FlattenException::createFromThrowable($exception, $message);
    }
}