<?php

	namespace lcd344\Panel\Helpers;

	use f;

	function loadPlugins($skip = []){
		$kirby = kirby();
		if (! is_array($kirby->plugins)) {
			$kirby->plugins = [];
		}

		$root = $kirby->roots->plugins();

		// check for an existing plugins dir
		if(!is_dir($root)) return $kirby->plugins;

		foreach(array_diff(scandir($root), array('.', '..')) as $file) {
			if(!in_array($file,$skip)){
				if(is_dir($root . DS . $file)) {
					$kirby->plugin($file, 'dir');
				} else if(f::extension($file) == 'php') {
					$kirby->plugin(f::name($file), 'file');
				}
			}
		}

	}