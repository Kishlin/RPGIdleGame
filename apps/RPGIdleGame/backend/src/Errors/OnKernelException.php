<?php

declare(strict_types=1);

namespace Kishlin\Apps\RPGIdleGame\Backend\Errors;

use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

final class OnKernelException
{
    /**
     * @var KernelExceptionHandler[]
     */
    private iterable $handlers;

    /**
     * @param KernelExceptionHandler[] $handlers
     */
    public function __construct(
        #[TaggedIterator('kishlin.errors.exception-handler')] iterable $handlers
    ) {
        $this->handlers = $handlers;
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $throwable = $event->getThrowable();

        foreach ($this->handlers as $handler) {
            $response = $handler->handle($throwable);

            if (null !== $response) {
                $event->setResponse($response);

                return;
            }
        }
    }
}
