<?php

/**
 * EnumTest.php – enums
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

namespace nxtlvlsoftware\tests\enums;

use PHPUnit\Framework\TestCase;

class EnumTest extends TestCase {

	/**
	 * Make sure integer enums are loaded correctly.
	 */
	public function testLoadsIntEnum() : void {
		$this->assertEquals(0, TypeEnums::INT()->getValue());
	}

	/**
	 * Make sure bool enums are loaded correctly.
	 */
	public function testLoadsBoolEnum() : void {
		$this->assertEquals(true, TypeEnums::BOOL()->getValue());
	}

	/**
	 * Make sure float enums are loaded correctly.
	 */
	public function testLoadsFloatEnum() : void {
		$this->assertEquals(24.44, TypeEnums::FLOAT()->getValue());
	}

	/**
	 * Make sure string enums are loaded correctly.
	 */
	public function testLoadsStringEnum() : void {
		$this->assertEquals("string enum", TypeEnums::STRING()->getValue());
	}

	/**
	 * Make sure array enums are loaded correctly.
	 */
	public function testLoadsArrayEnum() : void {
		$this->assertEquals([1, 2, 3], TypeEnums::ARRAY()->getValue());
	}

}