Enumerations for PHP
===============
_Brings class-based enums to PHP!_

[![Build Status](https://travis-ci.org/NxtLvLSoftware/php-enums.svg?branch=master)](https://travis-ci.org/NxtLvLSoftware/php-enums)

### About

This package provides you with a quick and easy way to implement enumerations in your PHP codebase. The purpose of this
package is to make defining enums quick and do it with as least code as possible. This is achieved by reading constants
from a class providing and then providing access to them through magic static methods that have the same name as the constant.
We use this approach to wrap the underlying constant value inside an immutable object that remains the same throughout the
current runtime. Using an object rather than the underlying value of the constant helps to replicate the behaviour of enums
in other programming languages (an enum is not treated as an int even through deep down it is) and aims to deter the reliance
on 'magic numbers' (constant values which rarely change but are not guaranteed to remain the same) by hiding the underlying value.

Here's a quick example:
```php
final class MyEnum extends nxtlvlsoftware\enums\Enum {
    protected const ENUM_INT = 0;
    protected const ENUM_STRING = "string";
}
```

We can then access these declared enums by calling a static method on the class with the equivalent name:

```php
MyEnum::ENUM_INT();
MyEnum::ENUM_STRING();
```

We use protected constants in this example to hide them from other classes, we only want them accessed through the static
method. The enum class is also declared as final to stop other other classes extending it.

### Installation

All you have to do to install with composer is the following:

```bash
$ composer require nxtlvlsoftware/enums
```

Or add it directly to your composer.json manifest:

```json
{
    "require": {
        "nxtlvlsoftware/enums": "*"
    }
}
```

The library will start working out of the box, just make sure you extend the base enum class or the static methods won't
be defined on your class.

### IDE Auto-completion

You may be thinking to yourself `Cool, but my IDE won't know these 'enums' even exist!` and you'd be right.
An IDE such as PhpStorm will have no idea these magic methods exist which is why we provide an 'IDE helper generator'.
It's a simple CLI application that will scan a directory for enum classes and generate a file [like this](https://gist.github.com/JackNoordhuis/a73dbf5cd32dac4ce44ad9177add3816)
that will tell your IDE that these methods actually will exist when the code runs.

If you require this package globally with composer the command will be available anywhere on your machine as `enums` or any
project which has this package as a dependency will link the script into the projects bin directory (`vendor/bin/enums` by default).

You will have to define at least one directory to scan and the location and filename of the stubs:
```bash
$ php vendor/bin/enums --dir /Users/Jack/Projects/MyProject/src --dir /Users/Jack/Projects/MyProject/vendor --out /Users/Jack/Projects/MyProject/enum_stubs.php
```

This command will scan the source directory of the project and all of it's installed composer dependencies and generate
the IDE helper stubs in the `enum_stubs.php` file.

You can use the shortcut option names `-d` for specifying a directory and `-o` for the output file instead of the full names.
Creating a simple script or PhpStorm configuration to quickly run this command will probably improve your workflow as
typing the whole command out every time you update one of your enum classes will get very tiresome.

### Under the hood

The first time an enum is accessed on a class the library uses reflection to fetch all the constants defined, it then stores
a new instance of the class (in an array) which internally stores the enum value and name. This allows us to implement the
magic to string method to display the name of the enum for debugging purposes. The value is stored for potential a future
feature that will allow an underlying value to be returned in place of the enum object for special use-cases.

### Issues

Found a problem with this library? Make sure to open an issue on the [issue tracker](https://github.com/NxtLvLSoftware/php-enums/issues) and we'll get it sorted!

#

__The content of this repo is licensed under the Unlicense. A full copy of the license is available [here](LICENSE).__