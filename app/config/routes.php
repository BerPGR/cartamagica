<?php

use app\middlewares\SecurityHeadersMiddleware;
use flight\Engine;
use flight\net\Router;

require_once __DIR__ . "/../controllers/AuthController.php";
require_once __DIR__ . "/../controllers/ChatController.php";
require_once __DIR__ . "/../controllers/CartasController.php";

/** 
 * @var Router $router 
 * @var Engine $app
 */

// This wraps all routes in the group with the SecurityHeadersMiddleware
$router->group('', function (Router $router) use ($app) {
	$csp = $app->get('csp_nonce');
	$router->get('/', function () use ($app, $csp) {
		$app->render('welcome', ['csp_nonce' => $csp]);
	});

	$router->get('/login', function () use ($app, $csp) {
		$app->render('login', ['csp_nonce' => $csp]);
	});

	$router->post("/login", function () use ($app) {
		try {
			(new AuthController($app))->login();
		} catch (\Throwable $e) {
			$app->redirect('/login');
		}
	});

	$router->post('/register', function () use ($app) {
		try {
			(new AuthController($app))->register();
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

	$router->get('/cartas/user/@userId', function ($userId) use ($app) {
		(new CartasController($app))->index($userId);
	});

	$router->get('/create', function () use ($app, $csp) {
		$_SESSION['interaction_id'] = null;
		$_SESSION['turno'] = 0;
		$app->render('create', ['csp_nonce' => $csp]);
	});

	$router->post('/chat', function () use ($app) {
		(new ChatController($app))->send();
	});

	$router->get('/pagamento/@cartaId', function () use ($app, $csp) {
		$app->render('pagamento', ['csp_nonce'=> $csp]);
	});
}, [SecurityHeadersMiddleware::class]);
