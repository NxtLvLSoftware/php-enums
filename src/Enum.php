<?php

/**
 * Enum.php – enums
 *
 * Copyright (C) 2019 Jack Noordhuis
 *
 * @author Jack Noordhuis
 *
 * This is free and unencumbered software released into the public domain.
 *
 * Anyone is free to copy, modify, publish, use, compile, sell, or
 * distribute this software, either in source code form or as a compiled
 * binary, for any purpose, commercial or non-commercial, and by any means.
 *
 * In jurisdictions that recognize copyright laws, the author or authors
 * of this software dedicate any and all copyright interest in the
 * software to the public domain. We make this dedication for the benefit
 * of the public at large and to the detriment of our heirs and
 * successors. We intend this dedication to be an overt act of
 * relinquishment in perpetuity of all present and future rights to this
 * software under copyright law.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE AUTHORS BE LIABLE FOR ANY CLAIM, DAMAGES OR
 * OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE,
 * ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
 * OTHER DEALINGS IN THE SOFTWARE.
 *
 * For more information, please refer to <http://unlicense.org/>
 *
 */

declare(strict_types=1);

namespace nxtlvlsoftware\enums;

use function count;
use nxtlvlsoftware\enums\exception\UndefinedEnumerationException;
use ReflectionClass;
use function var_dump;

abstract class Enum {

	/** @var Enum[] */
	protected static $enumerations = null;

	/**
	 * Load all constant-defined enums.
	 */
	protected static function loadEnumerations() : void {
		$reflection = new ReflectionClass(static::class);

		foreach($reflection->getConstants() as $name => $value) {
			static::enum($name, $value);
		}
	}

	/**
	 * Register an enumeration.
	 *
	 * @param string $name
	 * @param null   $value
	 */
	protected static function enum(string $name, $value = null) : void {
		static::$enumerations[$name] = new static($name, $value ?? count(static::$enumerations));
	}

	/**
	 * Use magic static methods to retrieve the enumerations.
	 *
	 * @param string $method
	 * @param array  $args
	 *
	 * @return \nxtlvlsoftware\enums\Enum
	 */
	public static function __callStatic(string $method, array $args) {
		if(static::$enumerations === null) {
			static::loadEnumerations();
		}

		if(isset(static::$enumerations[$method])) {
			return static::$enumerations[$method];
		}

		throw new UndefinedEnumerationException((new ReflectionClass(static::class))->getShortName() . "::" . $method . "()");
	}

	/** @var string */
	private $name;

	/** @var mixed */
	private $value;

	public function __construct(string $name, $value) {
		$this->name = $name;
		$this->value = $value;
	}

	public function __toString() {
		return (new ReflectionClass(static::class))->getShortName() . "::" . $this->name . "()";
	}

}