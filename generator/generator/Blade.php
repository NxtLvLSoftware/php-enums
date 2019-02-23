<?php

/**
 * Blade.php – enums
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

use Illuminate\Container\Container;
use Illuminate\Contracts\Container\Container as ContainerInterface;
use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Illuminate\View\ViewServiceProvider;

class Blade {

	/** @var array */
	protected $viewPaths;

	/** @var string */
	protected $cachePath;

	/** @var Container */
	protected $container;

	/** @var \Illuminate\View\Engines\EngineResolver */
	protected $engineResolver;

	/**
	 * Constructor.
	 *
	 * @param array       $viewPaths
	 * @param string             $cachePath
	 * @param ContainerInterface $container
	 */
	public function __construct(array $viewPaths, $cachePath, ContainerInterface $container = null) {
		$this->viewPaths = $viewPaths;
		$this->cachePath = $cachePath;
		$this->container = $container ?: new Container;

		$this->setupContainer();

		(new ViewServiceProvider($this->container))->register();

		$this->engineResolver = $this->container->make('view.engine.resolver');
	}

	/**
	 * Bind required instances for the service provider.
	 */
	protected function setupContainer() {
		$this->container->bindIf('files', function() {
			return new Filesystem;
		}, true);

		$this->container->bindIf('events', function() {
			return new Dispatcher;
		}, true);

		$this->container->bindIf('config', function() {
			return [
				'view.paths' => $this->viewPaths,
				'view.compiled' => $this->cachePath,
			];
		}, true);
	}

	/**
	 * Render shortcut.
	 *
	 * @param  string $view
	 * @param  array  $data
	 * @param  array  $mergeData
	 *
	 * @return string
	 */
	public function render($view, $data = [], $mergeData = []) {
		return $this->container['view']->make($view, $data, $mergeData)->render();
	}

	/**
	 * Get the compiler
	 *
	 * @return mixed
	 */
	public function compiler() {
		$bladeEngine = $this->engineResolver->resolve('blade');

		return $bladeEngine->getCompiler();
	}

	/**
	 * Pass any method to the view factory instance.
	 *
	 * @param  string $method
	 * @param  array  $params
	 *
	 * @return mixed
	 */
	public function __call($method, $params) {
		return call_user_func_array([$this->container['view'], $method], $params);
	}

}