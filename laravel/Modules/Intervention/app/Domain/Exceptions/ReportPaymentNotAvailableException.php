<?php

declare(strict_types=1);

namespace Modules\Intervention\Domain\Exceptions;

use RuntimeException;

/**
 * Thrown when an intervention report cannot be paid yet (no amount due,
 * missing signature, or already settled).
 */
final class ReportPaymentNotAvailableException extends RuntimeException {}
