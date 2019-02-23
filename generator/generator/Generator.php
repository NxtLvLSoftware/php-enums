<?php

/**
 * Generator.php – enums
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

namespace nxtlvlsoftware\generator\enums\generator;

use function fclose;
use function file_get_contents;
use function fopen;
use function fwrite;
use nxtlvlsoftware\generator\enums\generator\models\EnumClass;
use nxtlvlsoftware\generator\enums\parser\EnumClassDeclarationVisitor;
use nxtlvlsoftware\generator\enums\parser\EnumConstantDeclarationVisitor;
use PhpParser\Error;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\ParserFactory;
use SplFileInfo;

class Generator {

	/** @var \PhpParser\Parser */
	private $parser;

	/** @var \nxtlvlsoftware\generator\enums\generator\Blade */
	private $blade;

	public function __construct() {
		$this->parser = (new ParserFactory())->create(ParserFactory::ONLY_PHP7);
		$this->blade = new Blade([__DIR__ . "/views"], __DIR__ . "/../../cache");
	}

	public function generate(array $files, string $output) {
		$classes = [];

		foreach($files as $handle) {
			$stmts = $this->parse($handle);
			if($stmts !== null and ($class = $this->detect($stmts)) !== null) {
				$this->detectConstants($stmts, $class);
				$classes[] = $class;
			}
		}

		$handle = fopen($output, "w");
		fwrite($handle, $this->blade->make("enum_helper")
			->with("classes", $classes)->render());
		fclose($handle);
	}

	/**
	 * Scan a directory for valid php files.
	 *
	 * @param string $directory
	 *
	 * @return array
	 */
	public function scan(string $directory) : array {
		$files = [];
		$iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($directory));

		foreach($iterator as $file) {
			if($file instanceof SplFileInfo) {
				if($file->isFile() and $file->getExtension() === "php") {
					$files[] = $file->getPathname();
				}
			}
		}

		return $files;
	}

	/**
	 * Parse a file and return all the nodes.
	 *
	 * @param string $file
	 *
	 * @return \PhpParser\Node\Stmt[]|null
	 */
	protected function parse(string $file) : ?array {
		/** @var \PhpParser\Node\Stmt[]|null $stmts */
		$stmts = null;

		try {
			$stmts = $this->parser->parse(file_get_contents($file));
		} catch(Error $e) {

		}

		return $stmts;
	}

	/**
	 * Detect if a php file contains a valid enum class.
	 *
	 * @param \PhpParser\Node\Stmt[] $stmts
	 *
	 * @return \nxtlvlsoftware\generator\enums\generator\models\EnumClass|null
	 */
	protected function detect(array $stmts) : ?EnumClass {
		$traverser = new NodeTraverser;
		$traverser->addVisitor(new NameResolver);
		$traverser->addVisitor($visitor = new EnumClassDeclarationVisitor());
		$traverser->traverse($stmts);
		return $visitor->getEnumClass();
	}

	/**
	 * Scan a classes statements for enum constant declarations.
	 *
	 * @param array                                                      $stmts
	 * @param \nxtlvlsoftware\generator\enums\generator\models\EnumClass $class
	 */
	protected function detectConstants(array $stmts, EnumClass $class) : void {
		$traverser = new NodeTraverser;
		$traverser->addVisitor($visitor = new EnumConstantDeclarationVisitor($class));
		$traverser->traverse($stmts);
	}

}