<?php

declare(strict_types=1);

namespace Reinder83\BinaryFlags\Internal;

use Reinder83\BinaryFlags\Traits\BinaryFlags;

/**
 * @internal Helps static analysis see that the deprecated trait alias remains intentionally supported.
 */
final class LegacyBinaryFlagsTraitUsage
{
    use BinaryFlags;
}
