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
     *
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
                'codiceFiscale' => 'RSSMRA85T10A562S',
            ),
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
                    'birthDate' => 'This value is not a valid date.',
                ),
            ),
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
                    'gender' => 'Choose a valid gender (M or F).',
                ),
            ),
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
                    'omocodiaLevel' => 'This value should be of type numeric.',
                ),
            ),
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
                    'belfioreCode' => 'This value should not be blank.',
                ),
            ),
          ),
        );
    }

    /**
     * Test for calculate all.
     *
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
               'belfioreCode' => 'A562',
            ),
            array(
                'status' => true,
                'codiciFiscali' => array(
                    'RSSMRA85T10A562S',
                    'RSSMRA85T10A56NH',
                    'RSSMRA85T10A5S2E',
                    'RSSMRA85T10A5SNT',
                    'RSSMRA85T10AR62N',
                    'RSSMRA85T10AR6NC',
                    'RSSMRA85T10ARS2Z',
                    'RSSMRA85T10ARSNO',
                    'RSSMRA85T1LA562V',
                    'RSSMRA85T1LA56NK',
                    'RSSMRA85T1LA5S2H',
                    'RSSMRA85T1LA5SNW',
                    'RSSMRA85T1LAR62Q',
                    'RSSMRA85T1LAR6NF',
                    'RSSMRA85T1LARS2C',
                    'RSSMRA85T1LARSNR',
                    'RSSMRA85TM0A562D',
                    'RSSMRA85TM0A56NS',
                    'RSSMRA85TM0A5S2P',
                    'RSSMRA85TM0A5SNE',
                    'RSSMRA85TM0AR62Y',
                    'RSSMRA85TM0AR6NN',
                    'RSSMRA85TM0ARS2K',
                    'RSSMRA85TM0ARSNZ',
                    'RSSMRA85TMLA562G',
                    'RSSMRA85TMLA56NV',
                    'RSSMRA85TMLA5S2S',
                    'RSSMRA85TMLA5SNH',
                    'RSSMRA85TMLAR62B',
                    'RSSMRA85TMLAR6NQ',
                    'RSSMRA85TMLARS2N',
                    'RSSMRA85TMLARSNC',
                    'RSSMRA8RT10A562E',
                    'RSSMRA8RT10A56NT',
                    'RSSMRA8RT10A5S2Q',
                    'RSSMRA8RT10A5SNF',
                    'RSSMRA8RT10AR62Z',
                    'RSSMRA8RT10AR6NO',
                    'RSSMRA8RT10ARS2L',
                    'RSSMRA8RT10ARSNA',
                    'RSSMRA8RT1LA562H',
                    'RSSMRA8RT1LA56NW',
                    'RSSMRA8RT1LA5S2T',
                    'RSSMRA8RT1LA5SNI',
                    'RSSMRA8RT1LAR62C',
                    'RSSMRA8RT1LAR6NR',
                    'RSSMRA8RT1LARS2O',
                    'RSSMRA8RT1LARSND',
                    'RSSMRA8RTM0A562P',
                    'RSSMRA8RTM0A56NE',
                    'RSSMRA8RTM0A5S2B',
                    'RSSMRA8RTM0A5SNQ',
                    'RSSMRA8RTM0AR62K',
                    'RSSMRA8RTM0AR6NZ',
                    'RSSMRA8RTM0ARS2W',
                    'RSSMRA8RTM0ARSNL',
                    'RSSMRA8RTMLA562S',
                    'RSSMRA8RTMLA56NH',
                    'RSSMRA8RTMLA5S2E',
                    'RSSMRA8RTMLA5SNT',
                    'RSSMRA8RTMLAR62N',
                    'RSSMRA8RTMLAR6NC',
                    'RSSMRA8RTMLARS2Z',
                    'RSSMRA8RTMLARSNO',
                    'RSSMRAU5T10A562P',
                    'RSSMRAU5T10A56NE',
                    'RSSMRAU5T10A5S2B',
                    'RSSMRAU5T10A5SNQ',
                    'RSSMRAU5T10AR62K',
                    'RSSMRAU5T10AR6NZ',
                    'RSSMRAU5T10ARS2W',
                    'RSSMRAU5T10ARSNL',
                    'RSSMRAU5T1LA562S',
                    'RSSMRAU5T1LA56NH',
                    'RSSMRAU5T1LA5S2E',
                    'RSSMRAU5T1LA5SNT',
                    'RSSMRAU5T1LAR62N',
                    'RSSMRAU5T1LAR6NC',
                    'RSSMRAU5T1LARS2Z',
                    'RSSMRAU5T1LARSNO',
                    'RSSMRAU5TM0A562A',
                    'RSSMRAU5TM0A56NP',
                    'RSSMRAU5TM0A5S2M',
                    'RSSMRAU5TM0A5SNB',
                    'RSSMRAU5TM0AR62V',
                    'RSSMRAU5TM0AR6NK',
                    'RSSMRAU5TM0ARS2H',
                    'RSSMRAU5TM0ARSNW',
                    'RSSMRAU5TMLA562D',
                    'RSSMRAU5TMLA56NS',
                    'RSSMRAU5TMLA5S2P',
                    'RSSMRAU5TMLA5SNE',
                    'RSSMRAU5TMLAR62Y',
                    'RSSMRAU5TMLAR6NN',
                    'RSSMRAU5TMLARS2K',
                    'RSSMRAU5TMLARSNZ',
                    'RSSMRAURT10A562B',
                    'RSSMRAURT10A56NQ',
                    'RSSMRAURT10A5S2N',
                    'RSSMRAURT10A5SNC',
                    'RSSMRAURT10AR62W',
                    'RSSMRAURT10AR6NL',
                    'RSSMRAURT10ARS2I',
                    'RSSMRAURT10ARSNX',
                    'RSSMRAURT1LA562E',
                    'RSSMRAURT1LA56NT',
                    'RSSMRAURT1LA5S2Q',
                    'RSSMRAURT1LA5SNF',
                    'RSSMRAURT1LAR62Z',
                    'RSSMRAURT1LAR6NO',
                    'RSSMRAURT1LARS2L',
                    'RSSMRAURT1LARSNA',
                    'RSSMRAURTM0A562M',
                    'RSSMRAURTM0A56NB',
                    'RSSMRAURTM0A5S2Y',
                    'RSSMRAURTM0A5SNN',
                    'RSSMRAURTM0AR62H',
                    'RSSMRAURTM0AR6NW',
                    'RSSMRAURTM0ARS2T',
                    'RSSMRAURTM0ARSNI',
                    'RSSMRAURTMLA562P',
                    'RSSMRAURTMLA56NE',
                    'RSSMRAURTMLA5S2B',
                    'RSSMRAURTMLA5SNQ',
                    'RSSMRAURTMLAR62K',
                    'RSSMRAURTMLAR6NZ',
                    'RSSMRAURTMLARS2W',
                    'RSSMRAURTMLARSNL',
                ),
            ),
          ),
          array(
            array(
                'name' => 'Mario',
                'surname' => 'Rossi',
                'birthDate' => '1985-1-10',
                'gender' => 'M',
                'belfioreCode' => 'A562',
            ),
            array(
                'status' => false,
                'errors' => (object) array(
                    'birthDate' => 'This value is not a valid date.',
                ),
            ),
          ),
          array(
            array(
                'name' => 'Mario',
                'surname' => 'Rossi',
                'birthDate' => '1985-12-10',
                'gender' => 'B',
                'belfioreCode' => 'A562',
            ),
            array(
                'status' => false,
                'errors' => (object) array(
                    'gender' => 'Choose a valid gender (M or F).',
                ),
            ),
          ),
          array(
            array(
                'name' => 'Mario',
                'surname' => 'Rossi',
                'birthDate' => '1985-12-10',
                'gender' => 'M',
                'belfioreCode' => '123',
            ),
            array(
                'status' => false,
                'errors' => (object) array(
                    'belfioreCode' => 'This value should have exactly 4 characters.',
                ),
            ),
          ),
        );
    }

    /**
     * Test for check.
     *
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
                'message' => 'Valid codice fiscale',
            ),
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
                'message' => 'Invalid codice fiscale',
            ),
          ),
          array(
            array(
                'name' => 'Mario',
                'surname' => 'Rossi',
                'birthDate' => '1985-12-10',
                'gender' => 'M',
                'belfioreCode' => 'A562',
                'codiceFiscale' => 'RSSMRA85T10ARSNO',
            ),
            array(
                'status' => true,
                'message' => 'Valid codice fiscale',
            ),
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
                    'omocodiaLevel' => 'This value should be of type numeric.',
                ),
            ),
          ),
        );
    }
}
