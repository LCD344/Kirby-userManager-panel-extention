<?php
	namespace lcd344;

	if (class_exists('panel')) {

		$routes = require __DIR__ . '/routes.php';
		require __DIR__ . '/filters.php';

		$router = new \Router($routes);

		$router->filter('auth',authFilter());
		$router->filter('isInstalled',isInstalledFilter());

		$route = $router->run(kirby()->path());
		// Return if we didn't define a matching route to allow Kirby's router to process the request
		if (is_null($route)) return;
		// Call the route
		$controller = new $route->controller();

		$response = call([$controller,$route->action()], $route->arguments());
		// $response is the return value of the route's action, but we won't need that
		// Exit execution to stop Kirby from displaying the error page
		exit;
	}
	?>