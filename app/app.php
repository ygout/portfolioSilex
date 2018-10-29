<?php

use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;

// Register global error and exception handlers
ErrorHandler::register();
ExceptionHandler::register();

// Register service providers
$app->register(new Silex\Provider\DoctrineServiceProvider());
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/../views',
));
$app['twig'] = $app->share($app->extend('twig', function(Twig_Environment $twig, $app) {
                    $twig->addExtension(new Twig_Extensions_Extension_Text());
                    return $twig;
                }));

$app->register(new Silex\Provider\SecurityServiceProvider(), array(
    'security.firewalls' => array(
        'secured' => array(
            'pattern' => '^/',
            'anonymous' => true,
            'logout' => true,
            'form' => array('login_path' => '/login', 'check_path' => '/login_check'),
            'users' => $app->share(function () use ($app) {
                        return new Portfolio\DAO\UserDAO($app['db']);
                    }),
        ),
    ),
));
$app->register(new Silex\Provider\ValidatorServiceProvider());
$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
$app->register(new Silex\Provider\FormServiceProvider());
$app->register(new Silex\Provider\TranslationServiceProvider());
$app->register(new Silex\Provider\SwiftmailerServiceProvider());

$app['swiftmailer.options'] = array(
    'host' => 'smtp.gmail.com',
    'port' => 25,
    'username' => 'yohann.goutaret@gmail.com',
    'password' => '******',
    'encryption' => 'ssl',
    'auth_mode' => 'login'
);






// Register services.
$app['dao.categorie'] = $app->share(function ($app) {
            return new Portfolio\DAO\CategorieDAO($app['db']);
        });
$app['dao.user'] = $app->share(function ($app) {
            return new Portfolio\DAO\UserDAO($app['db']);
        });

$app['dao.projet'] = $app->share(function($app) {
            $projetDAO = new Portfolio\DAO\ProjetDAO($app['db']);
            $projetDAO->setCategorieDAO($app['dao.categorie']);
            return $projetDAO;
        });

