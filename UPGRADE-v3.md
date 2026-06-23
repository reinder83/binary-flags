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
2. Ensure values are cast or validated as `int` before passing them.
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

## Runtime Impact
Code paths that still pass `float` values into numeric flag APIs now fail with `TypeError` when called from `strict_types=1` code.
For non-strict callers, PHP scalar coercion can still convert `float` to `int` at the call boundary, so external values should be validated or cast before calling the API.

## Why BIT_64 Is Being Removed
`BIT_64` was removed because PHP numbers for bitwise flags are signed. The 64th bit is the sign bit, so it cannot be used reliably as a normal flag.

Staying with integer-compatible bits prevents those runtime issues.
