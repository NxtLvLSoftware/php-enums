<?php

/**
 * GenerateCommand.php – enums
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

namespace nxtlvlsoftware\generator\enums\commands;

use function array_merge;
use nxtlvlsoftware\generator\enums\generator\Generator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateCommand extends Command {

	protected static $defaultName = "generate";

	protected function configure() {
		$this
			->setDescription("Scans the specified directories for enum class to generate a stub file.")
			->addOption("dir", "d", InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED, "List the directories you wish to search for enum classes in (separate with a space):")
			->addOption("out", "o", InputOption::VALUE_REQUIRED, "The directory where the helper file will be generated.");
	}

	protected function execute(InputInterface $input, OutputInterface $output) {
		$generator = new Generator();
		$files = [];

		foreach($input->getOption("dir") as $dir) {
			$files = array_merge($files, $generator->scan($dir));
		}

		$generator->generate($files, $input->getOption("out"));
	}

}