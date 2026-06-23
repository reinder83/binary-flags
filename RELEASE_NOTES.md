# Release Notes - v3.0.0

## Changed
- Numeric `BinaryFlags` APIs now accept `int` values only.
- Passing `float` values to numeric mask/flag methods now raises `TypeError` for `strict_types=1` callers.
- Non-strict callers should still cast or validate external values before calling the API because PHP scalar coercion can convert `float` to `int` at the call boundary.
- The numeric iterator and JSON serialization contracts are now documented as `int`-based.

## Removed
- `Bits::BIT_64`.
- The v2.x float-normalization/deprecation path in `Traits\InteractsWithNumericFlags`.

## BIT_64 Notice
`Bits::BIT_64` was removed because PHP numbers for bitwise flags are signed. The 64th bit is the sign bit, so it cannot be used reliably as a normal flag.

Using integer-compatible bits avoids these issues.
