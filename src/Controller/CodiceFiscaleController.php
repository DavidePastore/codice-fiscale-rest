<?php

namespace DavidePastore\CodiceFiscaleRest\Controller;

use DavidePastore\CodiceFiscaleRest\Entity\Subject;
use CodiceFiscale\Calculator;
use CodiceFiscale\Checker;
use CodiceFiscale\Subject as CodiceFiscaleSubject;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class CrudController
 * @Route("/api/codiceFiscale")
 */
class CodiceFiscaleController extends AbstractController
{

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Calculate the codice fiscale by the given parameters.
     * @Route("/calculate", name="apiCalculate")
     */
    public function calculate(Request $request): Response
    {
        $data = array(
            'name' => $request->get('name', ''),
            'surname' => $request->get('surname', ''),
            'gender' => $request->get('gender', ''),
            'birthDate' => $request->get('birthDate', ''),
            'belfioreCode' => $request->get('belfioreCode', ''),
            'omocodiaLevel' => $request->get('omocodiaLevel', 0),
        );

        $subject = new Subject($data);

        $errors = $this->validator->validate($subject, null, 'calculate');
        $response = new \stdClass();

        if (count($errors) > 0) {
            $response = $this->generateResponseFromErrors($errors);
        } else {
            $subject = new CodiceFiscaleSubject($data);

            $calculator = new Calculator($subject, array(
                'omocodiaLevel' => $data['omocodiaLevel'],
            ));
            $codiceFiscale = $calculator->calculate();

            $response->status = true;
            $response->codiceFiscale = $codiceFiscale;
        }

        return new JsonResponse($response);
    }

    /**
     * Calculate all the codice fiscale by the given parameters.
     * @Route("/calculateAll", name="apiCalculateAll")
     */
    public function calculateAll(Request $request): Response
    {
        $data = array(
            'name' => $request->get('name', ''),
            'surname' => $request->get('surname', ''),
            'gender' => $request->get('gender', ''),
            'birthDate' => $request->get('birthDate', ''),
            'belfioreCode' => $request->get('belfioreCode', ''),
        );

        $subject = new Subject($data);

        $errors = $this->validator->validate($subject, null, 'calculateAll');
        $response = new \stdClass();

        if (count($errors) > 0) {
            $response = $this->generateResponseFromErrors($errors);
        } else {
            $subject = new CodiceFiscaleSubject($data);

            $calculator = new Calculator($subject);
            $codiciFiscali = $calculator->calculateAllPossibilities();

            $response->status = true;
            $response->codiciFiscali = $codiciFiscali;
        }

        return new JsonResponse($response);
    }

    /**
     * Check if the given parameters are ok for the given parameters.
     * @Route("/check", name="apiCheck")
     */
    public function check(Request $request): Response
    {
        $data = array(
            'name' => $request->get('name', ''),
            'surname' => $request->get('surname', ''),
            'gender' => $request->get('gender', ''),
            'birthDate' => $request->get('birthDate', ''),
            'belfioreCode' => $request->get('belfioreCode', ''),
            'codiceFiscale' => $request->get('codiceFiscale', ''),
            'omocodiaLevel' => $request->get('omocodiaLevel', Checker::ALL_OMOCODIA_LEVELS),
        );

        $subject = new Subject($data);

        $errors = $this->validator->validate($subject, null, 'check');
        $response = new \stdClass();

        if (count($errors) > 0) {
            $response = $this->generateResponseFromErrors($errors);
        } else {
            $subject = new CodiceFiscaleSubject($data);

            $checker = new Checker($subject, array(
              'codiceFiscaleToCheck' => $data['codiceFiscale'],
              'omocodiaLevel' => $data['omocodiaLevel'],
            ));

            if ($checker->check()) {
                $response->status = true;
                $response->message = 'Valid codice fiscale';
            } else {
                $response->status = false;
                $response->message = 'Invalid codice fiscale';
            }
        }

        return new JsonResponse($response);
    }

    /**
     * Generate response from the given ConstraintViolationList.
     *
     * @param $errors The ConstraintViolationList instance.
     *
     * @return Returns the response from the given errors.
     */
    private function generateResponseFromErrors(\Symfony\Component\Validator\ConstraintViolationList $errors)
    {
        $response = new \stdclass();
        $response->status = false;
        $responseErrors = array();

        foreach ($errors as $error) {
            $responseErrors[$error->getPropertyPath()] = $error->getMessage();
        }

        $response->errors = $responseErrors;

        return $response;
    }
}
