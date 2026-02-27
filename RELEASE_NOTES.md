# Release Notes - v2.1.0

## Added
- Deprecation warnings for passing `float` values as masks/flags.
- README migration notice for the upcoming `v3.0.0` integer-only API.
- `UPGRADE-v3.md` with migration instructions.
- New trait alias: `Traits\InteractsWithNumericFlags` (non-breaking alias for `Traits\BinaryFlags`).

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
