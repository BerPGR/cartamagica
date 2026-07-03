<?php

use app\middlewares\SecurityHeadersMiddleware;
use flight\Engine;
use flight\net\Router;
require_once __DIR__ . "/../controllers/AuthController.php";

/** 
 * @var Router $router 
 * @var Engine $app
 */

// This wraps all routes in the group with the SecurityHeadersMiddleware
$router->group('', function(Router $router) use ($app) {
	$csp = $app->get('csp_nonce');
	$router->get('/', function () use ($app, $csp) {
		$app->render('welcome', ['csp_nonce' => $csp]);
	});

	$router->get('/login', function() use ($app, $csp) {
		$app->render('login', ['csp_nonce' => $csp]);
	});

	$router->post("/login", function () use ($app) {
		$controller = new AuthController($app);
		try {
			$controller->login();
		} catch (\Throwable $e) {
			$app->redirect('/login');
		}
	});

	$router->post('/register', function () use ($app) {
		$controller = new AuthController($app);
		try {
			$controller->register();
		} catch (\Throwable $e) {
			$app->redirect('/');
		}
	});

	$router->get('/home', function () use ($app, $csp) {
		$app->render('home', ['csp_nonce' => $csp]);
	});

	$router->get('/cartas', function () use ($app, $csp) {
		$app->render('cartas', ['csp_nonce' => $csp]);
	});
}, [ SecurityHeadersMiddleware::class ]);