<?php
use DavidePastore\CodiceFiscale\Calculator;
use DavidePastore\CodiceFiscale\Checker;
use DavidePastore\CodiceFiscale\Subject;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Validator\Constraints as Assert;


$api = $app['controllers_factory'];

//Calculate the codice fiscale by the given parameters.
$api->get('/calculate', function (Silex\Application $app, Request $request) {
    $constraint = new Assert\Collection(array(
        'name' => $app['codice-fiscale-rest.constraints']['name'],
        'surname' => $app['codice-fiscale-rest.constraints']['surname'],
        'gender' => $app['codice-fiscale-rest.constraints']['gender'],
        'birthDate' => $app['codice-fiscale-rest.constraints']['birthDate'],
        'belfioreCode' => $app['codice-fiscale-rest.constraints']['belfioreCode'],
        'omocodiaLevel' => $app['codice-fiscale-rest.constraints']['omocodiaLevel']
    ));
    
    $data = array(
        'name' => $request->get('name', ''),
        'surname' => $request->get('surname', ''),
        'gender' => $request->get('gender', ''),
        'birthDate' => $request->get('birthDate', ''),
        'belfioreCode' => $request->get('belfioreCode', ''),
        'omocodiaLevel' => $request->get('omocodiaLevel', 0),
    );
    
    $errors = $app['validator']->validate($data, $constraint);
    $response = new stdClass;
    
    if (count($errors) > 0) {
        $response->status = false;
        $accessor = PropertyAccess::createPropertyAccessor();
        $responseErrors = array();
        
        foreach ($errors as $error) {
            $accessor->setValue($responseErrors, $error->getPropertyPath(), $error->getMessage());
        }
        
        $response->errors = $responseErrors;
    } else {
        $subject = new Subject($data);

        $calculator = new Calculator($subject, array(
            'omocodiaLevel' => $data['omocodiaLevel']
        ));
        $codiceFiscale = $calculator->calculate();
        
        $response->status = true;
        $response->codiceFiscale = $codiceFiscale;
    }
    return new JsonResponse($response);
})
->bind('apiCalculate');


//Calculate all the codice fiscale by the given parameters.
$api->get('/calculateAll', function (Silex\Application $app, Request $request) {
    $constraint = new Assert\Collection(array(
        'name' => $app['codice-fiscale-rest.constraints']['name'],
        'surname' => $app['codice-fiscale-rest.constraints']['surname'],
        'gender' => $app['codice-fiscale-rest.constraints']['gender'],
        'birthDate' => $app['codice-fiscale-rest.constraints']['birthDate'],
        'belfioreCode' => $app['codice-fiscale-rest.constraints']['belfioreCode']
    ));
    
    $data = array(
        'name' => $request->get('name', ''),
        'surname' => $request->get('surname', ''),
        'gender' => $request->get('gender', ''),
        'birthDate' => $request->get('birthDate', ''),
        'belfioreCode' => $request->get('belfioreCode', '')
    );
    
    $errors = $app['validator']->validate($data, $constraint);
    $response = new stdClass;
    
    if (count($errors) > 0) {
        $response->status = false;
        $accessor = PropertyAccess::createPropertyAccessor();
        $responseErrors = array();
        
        foreach ($errors as $error) {
            $accessor->setValue($responseErrors, $error->getPropertyPath(), $error->getMessage());
        }
        
        $response->errors = $responseErrors;
    } else {
        $subject = new Subject($data);

        $calculator = new Calculator($subject);
        $codiciFiscali = $calculator->calculateAllPossibilities();
        
        $response->status = true;
        $response->codiciFiscali = $codiciFiscali;
    }
    
    return new JsonResponse($response);
})
->bind('apiCalculateAll');

//Check if the given parameters are ok for the given parameters.
$api->get('/check', function (Silex\Application $app, Request $request) {
    $constraint = new Assert\Collection(array(
        'name' => $app['codice-fiscale-rest.constraints']['name'],
        'surname' => $app['codice-fiscale-rest.constraints']['surname'],
        'gender' => $app['codice-fiscale-rest.constraints']['gender'],
        'birthDate' => $app['codice-fiscale-rest.constraints']['birthDate'],
        'belfioreCode' => $app['codice-fiscale-rest.constraints']['belfioreCode'],
        'codiceFiscale' => $app['codice-fiscale-rest.constraints']['codiceFiscale'],
        'omocodiaLevel' => $app['codice-fiscale-rest.constraints']['omocodiaLevel'],
    ));
    
    $data = array(
        'name' => $request->get('name', ''),
        'surname' => $request->get('surname', ''),
        'gender' => $request->get('gender', ''),
        'birthDate' => $request->get('birthDate', ''),
        'belfioreCode' => $request->get('belfioreCode', ''),
        'codiceFiscale' => $request->get('codiceFiscale', ''),
        'omocodiaLevel' => $request->get('omocodiaLevel', Checker::ALL_OMOCODIA_LEVELS)
    );
    
    $errors = $app['validator']->validate($data, $constraint);
    $response = new stdClass;
    
    if (count($errors) > 0) {
        $response->status = false;
        $accessor = PropertyAccess::createPropertyAccessor();
        $responseErrors = array();
        
        foreach ($errors as $error) {
            $accessor->setValue($responseErrors, $error->getPropertyPath(), $error->getMessage());
        }
        
        $response->errors = $responseErrors;
    } else {
        $subject = new Subject($data);
        
        $checker = new Checker($subject, array(
          'codiceFiscaleToCheck' => $data['codiceFiscale'],
          'omocodiaLevel' => $data['omocodiaLevel']
        ));
        
        if ($checker->check()) {
            $response->status = true;
            $response->message = 'Valid codice fiscale';
        }
        else {
            $response->status = false;
            $response->message = 'Invalid codice fiscale';
        }
    }
    
    return new JsonResponse($response);
})
->bind('apiCheck');

return $api;