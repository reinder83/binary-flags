<?php

declare(strict_types=1);

namespace Reinder83\BinaryFlags\Traits;

/**
 * Backward-compatible alias for numeric mask/flag behavior.
 *
 * @deprecated Use InteractsWithNumericFlags in new code.
 */
trait BinaryFlags
{
    use InteractsWithNumericFlags;
}
