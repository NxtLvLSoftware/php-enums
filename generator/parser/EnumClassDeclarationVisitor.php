<?php

/**
 * EnumClassDeclarationVisitor.phpsitor.php – enums
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

namespace nxtlvlsoftware\generator\enums\parser;

use nxtlvlsoftware\enums\Enum;
use nxtlvlsoftware\generator\enums\generator\models\EnumClass;
use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;

class EnumClassDeclarationVisitor extends NodeVisitorAbstract {

	/** @var \nxtlvlsoftware\generator\enums\generator\models\EnumClass|null */
	private $enumClass = null;

	/** @var \PhpParser\Node\Stmt\Namespace_ */
	private $namespace;

	public function enterNode(Node $node) {
		if($node instanceof Node\Stmt\Namespace_) {
			$this->namespace = $node;
		} elseif($node instanceof Node\Stmt\Class_) {
			if($node->extends !== null and $node->extends->toString() === Enum::class) {
				$this->enumClass = new EnumClass($this->namespace->name->toString(), $node->name->toString());
			}
			return NodeTraverser::DONT_TRAVERSE_CHILDREN;
		}

		return null;
	}

	/**
	 * Get the model that represents this enum class.
	 *
	 * @return \nxtlvlsoftware\generator\enums\generator\models\EnumClass|null
	 */
	public function getEnumClass() : ?EnumClass {
		return $this->enumClass;
	}

}