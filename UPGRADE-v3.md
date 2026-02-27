# Upgrade Guide for v3.0.0

## Summary
`v3.0.0` removes support for `float` values in masks and flags.
`v3.0.0` also removes `Bits::BIT_64`.

## What Changed
- `v2.x`: `int|float` accepted in mask/flag methods.
- `v3.0.0`: only `int` is accepted.
- `v2.x`: `Bits::BIT_64` exists but is not reliable in real bitwise usage.
- `v3.0.0`: `Bits::BIT_64` is removed. Use `BIT_1` through `BIT_63`.

## How to Migrate
1. Find every call that passes mask/flag values into BinaryFlags methods.
2. Ensure values are cast to `int` before passing them.
3. Ensure database or external sources provide integer-compatible values.

## Example
Before:
```php
$flags->setMask($legacyValue);
$flags->addFlag($legacyFlag);
```

After:
```php
$flags->setMask((int) $legacyValue);
$flags->addFlag((int) $legacyFlag);
```

## v2.1+ Deprecation Signal
Starting in `v2.1.0`, float inputs trigger deprecation warnings to help detect call sites before moving to `v3.0.0`.

## Why BIT_64 Is Being Removed
`BIT_64` is being removed because PHP numbers for bitwise flags are signed. The 64th bit is the sign bit, so it cannot be used reliably as a normal flag.

Staying with integer-compatible bits prevents those runtime issues.
