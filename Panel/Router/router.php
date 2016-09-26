<?php
	namespace lcd344\Panel\Router;

	use lcd344\Mailer;

	if (class_exists('panel')) {

		require __DIR__ . '/filters.php';

		$userManagerRoutes = require __DIR__ . '/userManagerRoutes.php';
		$router = new \Router($userManagerRoutes);

		if(class_exists(Mailer::class)){
			$userMailerRoutes = require __DIR__ . "/userMailerRoutes.php";
			$router->register($userMailerRoutes);
		}

		$router->filter('auth',authFilter());
		$router->filter('isInstalled',isInstalledFilter());

		$route = $router->run(kirby()->path());
		// Return if we didn't define a matching route to allow Kirby's router to process the request
		if (is_null($route)) return;
		// Call the route

		\lcd344\Panel\Helpers\loadPlugins(['userManager']);

		$controller = new $route->controller();

		$response = call([$controller,$route->action()], $route->arguments());
		// $response is the return value of the route's action, but we won't need that
		// Exit execution to stop Kirby from displaying the error page
		exit;
	}
	?>