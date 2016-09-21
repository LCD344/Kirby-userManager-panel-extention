<?php
	namespace lcd344;

	use Kirby\Panel\Installer;

	/**
	 * Filter to check if user is authorized, Tried to copy this like in the panel but it doesn't work because the panel overides my exception catching
	 *
	 * @return \Closure
	 */
	function authFilter() {
		return function () {
			if (!kirby()->site()->user()) {
				panel()->redirect('login');
			}
		};
	}

	/**
	 *
	 * Filter to check if kirby is installed.
	 *
	 * @return \Closure
	 */
	function isInstalledFilter() {
		return function () {
			$installer = new Installer();
			if (!$installer->isCompleted()) {
				panel()->redirect('install');
			}
		};
	}