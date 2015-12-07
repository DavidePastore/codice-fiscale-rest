<?php
use Silex\WebTestCase;

class ApiTest extends WebTestCase
{
    public function createApplication()
    {
        return require __DIR__.'/../app.php';
    }
    
    /**
     * Test for calculate.
     * @dataProvider calculateProvider
     */
    public function testCalculate($parameters, $expected)
    {
        $client = $this->createClient();
        
        $client->request(
            'GET',
            '/api/codiceFiscale/calculate',
            $parameters
        );
        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            )
        );
        
        $content = json_decode($client->getResponse()->getContent());
        $this->assertEquals(
            (object) $expected,
            $content
        );
    }
    
    /**
     * The calculate provider.
     */
    public function calculateProvider()
    {
        return array(
          array(
            array(
                'name' => 'Mario',
                'surname' => 'Rossi',
                'birthDate' => '1985-12-10',
                'gender' => 'M',
                'belfioreCode' => 'A562',
                'omocodiaLevel' => 0,
            ),
            array(
                'status' => true,
                'codiceFiscale' => 'RSSMRA85T10A562S'
            )
          ),
          array(
            array(
                'name' => 'Mario',
                'surname' => 'Rossi',
                'birthDate' => '1985-1-10',
                'gender' => 'M',
                'belfioreCode' => 'A562',
                'omocodiaLevel' => 1,
            ),
            array(
                'status' => false,
                'errors' => (object) array(
                    'birthDate' => 'This value is not a valid date.'
                )
            )
          ),
          array(
            array(
                'name' => 'Mario',
                'surname' => 'Rossi',
                'birthDate' => '1985-12-10',
                'gender' => 'A',
                'belfioreCode' => 'A562',
                'omocodiaLevel' => 2,
            ),
            array(
                'status' => false,
                'errors' => (object) array(
                    'gender' => 'Choose a valid gender (M or F).'
                )
            )
          ),
          array(
            array(
                'name' => 'Mario',
                'surname' => 'Rossi',
                'birthDate' => '1985-12-10',
                'gender' => 'M',
                'belfioreCode' => 'A562',
                'omocodiaLevel' => 'A',
            ),
            array(
                'status' => false,
                'errors' => (object) array(
                    'omocodiaLevel' => 'This value should be of type numeric.'
                )
            )
          ),
          array(
            array(
                'name' => 'Mario',
                'surname' => 'Rossi',
                'birthDate' => '1985-12-10',
                'gender' => 'M',
                'belfioreCode' => '',
                'omocodiaLevel' => 1,
            ),
            array(
                'status' => false,
                'errors' => (object) array(
                    'belfioreCode' => 'This value should not be blank.'
                )
            )
          ),
        );
    }
    
    /**
     * Test for calculate all.
     * @dataProvider calculateAllProvider
     */
    public function testCalculateAll($parameters, $expected)
    {
        $client = $this->createClient();
        
        $client->request(
            'GET',
            '/api/codiceFiscale/calculateAll',
            $parameters
        );
        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            )
        );
        
        $content = json_decode($client->getResponse()->getContent());
        $this->assertEquals(
            (object) $expected,
            $content
        );
    }
    
    /**
     * The calculateAll provider.
     */
    public function calculateAllProvider()
    {
        return array(
          array(
            array(
               'name' => 'Mario',
               'surname' => 'Rossi',
               'birthDate' => '1985-12-10',
               'gender' => 'M',
               'belfioreCode' => 'A562'
            ),
            array(
                'status' => true,
                'codiciFiscali' => array(
                    'RSSMRA85T10A562S',
                    'RSSMRA85T10A56NH',
                    'RSSMRA85T10A5SNT',
                    'RSSMRA85T10ARSNO',
                    'RSSMRA85T1LARSNR',
                    'RSSMRA85TMLARSNC',
                    'RSSMRA8RTMLARSNO',
                    'RSSMRAURTMLARSNL'
                )
            )
          ),
          array(
            array(
                'name' => 'Mario',
                'surname' => 'Rossi',
                'birthDate' => '1985-1-10',
                'gender' => 'M',
                'belfioreCode' => 'A562'
            ),
            array(
                'status' => false,
                'errors' => (object) array(
                    'birthDate' => 'This value is not a valid date.'
                )
            )
          ),
          array(
            array(
                'name' => 'Mario',
                'surname' => 'Rossi',
                'birthDate' => '1985-12-10',
                'gender' => 'B',
                'belfioreCode' => 'A562'
            ),
            array(
                'status' => false,
                'errors' => (object) array(
                    'gender' => 'Choose a valid gender (M or F).'
                )
            )
          ),
          array(
            array(
                'name' => 'Mario',
                'surname' => 'Rossi',
                'birthDate' => '1985-12-10',
                'gender' => 'M',
                'belfioreCode' => '123'
            ),
            array(
                'status' => false,
                'errors' => (object) array(
                    'belfioreCode' => 'This value should have exactly 4 characters.'
                )
            )
          ),
        );
    }
    
    /**
     * Test for check.
     * @dataProvider checkerProvider
     */
    public function testCheck($parameters, $expected)
    {
        $client = $this->createClient();
        
        $client->request(
            'GET',
            '/api/codiceFiscale/check',
            $parameters
        );
        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            )
        );
        
        $content = json_decode($client->getResponse()->getContent());
        $this->assertEquals(
            (object) $expected,
            $content
        );
    }
    
    /**
     * The checker provider.
     */
    public function checkerProvider()
    {
        return array(
          array(
            array(
                'name' => 'Mario',
                'surname' => 'Rossi',
                'birthDate' => '1985-12-10',
                'gender' => 'M',
                'belfioreCode' => 'A562',
                'codiceFiscale' => 'RSSMRA85T10A562S',
                'omocodiaLevel' => 0,
            ),
            array(
                'status' => true,
                'message' => 'Valid codice fiscale'
            )
          ),
          array(
            array(
                'name' => 'Mario',
                'surname' => 'Rossi',
                'birthDate' => '1985-12-10',
                'gender' => 'M',
                'belfioreCode' => 'A562',
                'codiceFiscale' => 'RSSMRA85T10A562S',
                'omocodiaLevel' => 1,
            ),
            array(
                'status' => false,
                'message' => 'Invalid codice fiscale'
            )
          ),
          array(
            array(
                'name' => 'Mario',
                'surname' => 'Rossi',
                'birthDate' => '1985-12-10',
                'gender' => 'M',
                'belfioreCode' => 'A562',
                'codiceFiscale' => 'RSSMRA85T10ARSNO'
            ),
            array(
                'status' => true,
                'message' => 'Valid codice fiscale'
            )
          ),
          array(
            array(
                'name' => 'Mario',
                'surname' => 'Rossi',
                'birthDate' => '1985-12-10',
                'gender' => 'M',
                'belfioreCode' => 'A562',
                'codiceFiscale' => 'RSSMRA85T10A562S',
                'omocodiaLevel' => 'A',
            ),
            array(
                'status' => false,
                'errors' => (object) array(
                    'omocodiaLevel' => 'This value should be of type numeric.'
                )
            )
          ),
        );
    }
}