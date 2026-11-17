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
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class CrudController
 */
#[Route('/api/codiceFiscale')]
class CodiceFiscaleController extends AbstractController
{
    public function __construct(private ValidatorInterface $validator)
    {
    }

    /**
     * Calculate the codice fiscale by the given parameters.
     */
    #[Route('/calculate', name: 'apiCalculate')]
    public function calculate(Request $request): Response
    {
        $data = array(
            'name' => $this->requestValue($request, 'name', ''),
            'surname' => $this->requestValue($request, 'surname', ''),
            'gender' => $this->requestValue($request, 'gender', ''),
            'birthDate' => $this->requestValue($request, 'birthDate', ''),
            'belfioreCode' => $this->requestValue($request, 'belfioreCode', ''),
            'omocodiaLevel' => $this->requestValue($request, 'omocodiaLevel', 0),
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
     */
    #[Route('/calculateAll', name: 'apiCalculateAll')]
    public function calculateAll(Request $request): Response
    {
        $data = array(
            'name' => $this->requestValue($request, 'name', ''),
            'surname' => $this->requestValue($request, 'surname', ''),
            'gender' => $this->requestValue($request, 'gender', ''),
            'birthDate' => $this->requestValue($request, 'birthDate', ''),
            'belfioreCode' => $this->requestValue($request, 'belfioreCode', ''),
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
     */
    #[Route('/check', name: 'apiCheck')]
    public function check(Request $request): Response
    {
        $data = array(
            'name' => $this->requestValue($request, 'name', ''),
            'surname' => $this->requestValue($request, 'surname', ''),
            'gender' => $this->requestValue($request, 'gender', ''),
            'birthDate' => $this->requestValue($request, 'birthDate', ''),
            'belfioreCode' => $this->requestValue($request, 'belfioreCode', ''),
            'codiceFiscale' => $this->requestValue($request, 'codiceFiscale', ''),
            'omocodiaLevel' => $this->requestValue($request, 'omocodiaLevel', Checker::ALL_OMOCODIA_LEVELS),
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
     * Get a request value looking, in order, at the route attributes, the query
     * string and the request body (same lookup order as the deprecated Request::get()).
     */
    private function requestValue(Request $request, string $key, mixed $default = null): mixed
    {
        foreach ([$request->attributes, $request->query, $request->request] as $bag) {
            if ($bag->has($key)) {
                return $bag->all()[$key];
            }
        }

        return $default;
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
