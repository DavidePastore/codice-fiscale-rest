<?php

namespace DavidePastore\CodiceFiscaleRest\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class Subject
{
    public function __construct($data)
    {
        $this->name = $data['name'];
        $this->surname = $data['surname'];
        $this->gender = $data['gender'];
        $this->birthDate = $data['birthDate'];
        $this->belfioreCode = $data['belfioreCode'];
        $this->omocodiaLevel = $data['omocodiaLevel'] ?? '';
        $this->codiceFiscale = $data['codiceFiscale'] ?? '';
    }

    /**
     * @Assert\NotNull(groups={"calculate", "calculateAll", "check"})
     */
    private $name;
    
    /**
     * @Assert\NotNull(groups={"calculate", "calculateAll", "check"})
     */
    private $surname;


    /**
     * @Assert\Choice(
     *      groups={"calculate", "calculateAll", "check"},
     *      choices={"M", "F"},
     *      message="Choose a valid gender (M or F)."
     * )
     */
    private $gender;

    /**
     * @Assert\Date(groups={"calculate", "calculateAll", "check"})
     */
    private $birthDate;

    /**
     * @Assert\Sequentially({
     *     @Assert\NotBlank(groups={"calculate", "calculateAll", "check"}),
     *     @Assert\Length(
     *      groups={"calculate", "calculateAll", "check"},
     *      min = 4,
     *      max = 4
     *     )
     * })
     */
    private $belfioreCode;

    /**
     * @Assert\Type(
     *      groups={"calculate", "check"},
     *      type="numeric"
     * )
     */
    private $omocodiaLevel;

    /**
     * @Assert\NotNull(groups={"check"})
     */
    private $codiceFiscale;
}
