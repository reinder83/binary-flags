# Release Notes - v2.1.0

## Added
- New enum-backed API:
  - `BinaryEnumFlags`
  - `Traits\InteractsWithEnumFlags`
  - `Flag` enum and `Mask` value object
- Enum-backed flags now return a `Mask` object from `getMask()`.
- New `getMaskValue(): int` method for enum-backed flags to persist/interoperate with integer masks.
- Deprecation warnings for passing `float` values as masks/flags.
- README migration notice for the upcoming `v3.0.0` integer-only API.
- `UPGRADE-v3.md` with migration instructions.
- New primary numeric trait: `Traits\InteractsWithNumericFlags`.
- `Traits\BinaryFlags` is now deprecated and kept for backward compatibility.

## Deprecated
- Passing `float` to BinaryFlags mask/flag methods is deprecated in `v2.1.0`.
- Float support will be removed in `v3.0.0`.
- `Bits::BIT_64` will be removed in `v3.0.0`.

## BIT_64 Notice
`Bits::BIT_64` is being removed because PHP numbers for bitwise flags are signed. The 64th bit is the sign bit, so it cannot be used reliably as a normal flag.

Using integer-compatible bits avoids these issues.

## Migration Recommendation
Cast external/legacy mask and flag values to `int` before calling BinaryFlags methods.

```php
$flags->setMask((int) $mask);
$flags->addFlag((int) $flag);
```

## Enum Migration Example
```php
// Before: numeric PermissionFlags
class PermissionFlags extends BinaryFlags
{
    public const CAN_VIEW = Bits::BIT_1;
    public const CAN_BOOK = Bits::BIT_2;
}

$flags = new PermissionFlags($storedMask);
$flags->addFlag(PermissionFlags::CAN_VIEW | PermissionFlags::CAN_BOOK);
$storedMask = $flags->getMask();

// After: enum-backed PermissionFlags
enum Permission: int
{
    case CanView = Bits::BIT_1;
    case CanBook = Bits::BIT_2;
}

class PermissionFlags extends BinaryEnumFlags
{
    protected static function getFlagEnumClass(): string
    {
        return Permission::class;
    }
}

$flags = new PermissionFlags(Mask::fromInt($storedMask, Permission::class));
$flags->addFlag(Permission::CanView);
$flags->addFlag(Permission::CanBook);
$storedMask = $flags->getMaskValue();
```
