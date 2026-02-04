<?php

declare(strict_types=1);

namespace LaravelNepal\NCM\Exceptions;

use Exception;
use Throwable;

/**
 * Base exception for all Nepal Can Move related errors.
 */
final class NCMException extends Exception
{
    public function __construct(
        string $message = 'An error occurred with Nepal Can Move.',
        int $code = 0,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
