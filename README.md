[![Build Status](https://travis-ci.org/reinder83/binary-flags.svg?branch=master)](https://travis-ci.org/reinder83/binary-flags)
[![Coverage Status](https://coveralls.io/repos/github/reinder83/binary-flags/badge.svg?branch=master)](https://coveralls.io/github/reinder83/binary-flags?branch=master)

# BinaryFlags
With this class you can easily add flags to your models.
  
The number of flags you can use is limited to the architecture of your system, e.g.: 32 flags on a 32-bit system or 64 flags on 64-bit system. 
To store 64-bits flags in a database, you will need to store it as UNSIGNED BIGINT in MySQL or an equivalant in your datastorage.

This package also comes with a trait which use can use to implement binary flags directly in your own class.

## Installing
To install this package simply run the following command on the root of your project.
```
composer require reinder83\binary-flags
```

## Methods
The following methods can be used:

##### setMask(int $mask)
Overwrite the current mask.
This can be passed as first argument in the constructor.

##### getMask()
Retrieve the current mask.

##### setOnModifyCallback(callable $onModify)
Set a callback function which is called when the mask changes. 
This can be passed as second argument in the contructor.

##### getFlagNames([int $mask, [bool $asArray]])
Give the name(s) for the given `$mask` or the current `$mask` when omitted.
When `$asArray` is `true` the method will return an array with the names, 
otherwise an comma separated string will be returned.

##### addFlag(int $flag)
Adds one or multiple flags the to current mask.

##### removeFlag(int $flag)
Removes one or multiple flags from the current mask.

##### checkFlag(int $flag, [bool $checkAll=true])
Check if given flag(s) are set in the current mask. 
By default it will check all bits in the given flag. 
When you want to match any of the given flags set `$checkAll` to `false`.

## Example usage

Below some example usage code

##### Create classes
```php
// example classes which the following examples will refer to
use Reinder83\BinaryFlags\BinaryFlags;
use Reinder83\BinaryFlags\Bits;

class ExampleFlags extends BinaryFlags
{
    const FOO = Bits::BIT_1;
    const BAR = Bits::BIT_2;
    const BAZ = Bits::BIT_3;
}
```

##### Simple usage
```php
$exampleFlags = new ExampleFlags();

// add BAR flag
$exampleFlags->addFlag(ExampleFlags::BAR);

var_export($exampleFlags->checkFlag(ExampleFlags::FOO)); 
// false
var_export($exampleFlags->checkFlag(ExampleFlags::BAR)); 
// true

// remove BAR flag
$exampleFlags->removeFlag(ExampleFlags::BAR);

var_export($exampleFlags->checkFlag(ExampleFlags::BAR)); 
// false
```

##### Usage with multiple flags
```php
$exampleFlags = new ExampleFlags();

// add FOO and BAR
$exampleFlags->addFlag(ExampleFlags::FOO | ExampleFlags::BAR); 

var_export($exampleFlags->checkFlag(ExampleFlags::FOO)); 
// true

var_export($exampleFlags->checkFlag(ExampleFlags::FOO | ExampleFlags::BAZ)); 
// false because BAZ is not set

var_export($exampleFlags->checkFlag(ExampleFlags::FOO | ExampleFlags::BAR)); 
// true because both flags are set

var_export($exampleFlags->checkFlag(ExampleFlags::FOO | ExampleFlags::BAZ, true)); 
// true because one of the flags is set (FOO)
```

##### Flag names example
_By default the flag names are based on the constant names_
```php
$exampleFlags = new ExampleFlags();

$exampleFlags->addFlag(ExampleFlags::FOO | ExampleFlags::BAR | ExampleFlags::BAZ);
var_export($exampleFlags->getFlagNames());
// 'Foo, Bar, Baz'

// null will force current mask
var_export($exampleFlags->getFlagNames(null, true));
/*
array (
  0 => 'Foo',
  1 => 'Bar',
  2 => 'Baz',
)
*/

// get mask of given mask
var_export($exampleFlags->getFlagNames(ExampleFlags::FOO | ExampleFlags::BAR));
// 'Foo, Bar'
```

##### Custom flag names example
If you want custom flag names that are not equal to the constant names, you can override these with `getAllFlags()`

```php
class ExampleFlagsWithNames extends BinaryFlags
{
    const FOO = Bits::BIT_1;
    const BAR = Bits::BIT_2;
    const BAZ = Bits::BIT_3;
    
    public static function getAllFlags()
    {
        return [
            static::FOO => 'My foo description',
            static::BAR => 'My bar description',
            static::BAZ => 'My baz description',
        ];
    }
}

$exampleFlags = new ExampleFlagsWithNames();

$exampleFlags->addFlag(ExampleFlags::FOO | ExampleFlags::BAR | ExampleFlags::BAZ);

// null will force current mask
var_export($exampleFlags->getFlagNames(null, true));
/*
array (
  0 => 'My foo description',
  1 => 'My bar description',
  2 => 'My baz description',
)
*/
```

##### Example usage with Eloquent models

```php
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    /**
     * Retrieve flags
     * @return ExampleFlags
     */
    public function getFlagsAttribute()
    {
        static $flags = null;
        if ($flags === null) {
            $model = $this;
            $flags = new ExampleFlags(
                $this->attributes['flags'], // set current flags mask
                function (ExampleFlags $flags) use ($model) { // set callback function
                    // update the flags in this model
                    $model->flags = $flags->getMask();
                }
            );
        }
        return $flags;
    }
}

// retrieve object from DB
$test = Test::find(1);

// do binary operations on the flags class as described earlier
$test->flags->checkFlag(ExampleFlag::FOO);
```

## Support
For bugs or feature requests feel free to contact me or submit an issue or pull request. 