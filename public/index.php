<?php

define('APP_PATH', dirname(__DIR__)); // PHP v5.3+

require APP_PATH . '/vendor/autoload.php';
require APP_PATH . '/config/config.php';

$app = New \SlimController\Slim(array(
    'templates.path'             => APP_PATH . '/src/Wallfav/Views',
    'controller.class_prefix'    => '\\Wallfav\\Controllers',
    'controller.method_suffix'   => 'Action',
    'controller.template_suffix' => 'php',
    'debug' => true,
    'mode' => 'development',
    'view' => new customView(),
    ));

$app->setName('Wallfav');

// Rotas
$app->addRoutes($array_global_configs['website_rotas']);

// Página não encontrada
$app->notFound(function () use ($app) {
    $req           = $app->request;
    $referencia    = $req->getReferrer();
    $env           = $app->environment;
    $pagina_actual = $env['PATH_INFO'];
    $app->log->error("ERRO 404 - de $referencia para $pagina_actual");
    $app->redirect('/not_found');
});

// Controlar resposta
$res           = $app->response;
$proibido      = $res->isForbidden();
$erro_servidor = $res->isServerError();
$nao_encontrou = $res->isNotFound();
if ($proibido) {
    $app->redirect('/without_permission');
}
if ($erro_servidor) {
    $app->redirect('/error');
}

// Create monolog logger and store logger in container as singleton
// (Singleton resources retrieve the same log resource definition each time)
$app->container->singleton('log', function () {
    $log = new \Monolog\Logger('Wallfav');
    $log->pushHandler(new \Monolog\Handler\StreamHandler('../logs/app.log', \Monolog\Logger::DEBUG));
    return $log;
});

$app->run();

?>
