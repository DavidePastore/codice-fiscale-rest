<?php
require_once __DIR__.'/bootstrap.php';

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Constraints as Assert;

$app = new Silex\Application();

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
));

$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
$app->register(new Silex\Provider\ValidatorServiceProvider());

//Some custom constraints
$app['codice-fiscale-rest.constraints'] = array(
    'name' => new Assert\NotNull(),
    'surname' => new Assert\NotNull(),
    'gender' => new Assert\Choice(array(
        'choices' => array('M', 'F'),
        'message' => 'Choose a valid gender (M or F).'
    )),
    'birthDate' => new Assert\Date(),
    'belfioreCode' => new Assert\Length(array(
        'min' => 4,
        'max' => 4
    )),
    'omocodiaLevel' => new Assert\Type(array(
        'type'    => 'numeric'
    )),
    'codiceFiscale' => new Assert\NotNull()
);

//Home page
$app->get('/', function (Silex\Application $app) {
    return $app['twig']->render('index.twig');
})
->bind('homepage');

//Documentazione
$app->get('/documentazione', function (Silex\Application $app) {
    return $app['twig']->render('documentazione.twig');
})
->bind('documentazione');

//Prova
$app->get('/prova', function (Silex\Application $app) {
    return $app['twig']->render('prova.twig');
})
->bind('prova');

$app->error(function (\Exception $e, $code) {
    switch ($code) {
        case 404:
            $message = 'The requested page could not be found.';
            break;
        default:
            $message = 'We are sorry, but something went terribly wrong.';
            echo $e;
    }

    return new Response($message);
});

$app->mount('/api/codiceFiscale', new DavidePastore\CodiceFiscaleRest\CodiceFiscaleControllerProvider());

$app->after(function (Request $request, Response $response) {
    $response->headers->set('Access-Control-Allow-Origin', '*');
});

return $app;