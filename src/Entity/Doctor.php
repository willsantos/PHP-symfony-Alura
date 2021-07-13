<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity()
 */
class Doctor implements \JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="integer")
     */
    private $crm;
    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=Specialty::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $specialty;

    public function getSpecialty(): ?Specialty
    {
        return $this->specialty;
    }

    public function setSpecialty(?Specialty $specialty): self
    {
        $this->specialty = $specialty;

        return $this;
    }



    /**
     * Get the value of id
     */ 
    public function getId(): ?int
    {
        return $this->id;
    }

   

    /**
     * Get the value of crm
     */ 
    public function getCrm(): ?int
    {
        return $this->crm;
    }

    /**
     * Set the value of crm
     *
     * @return  self
     */ 
    public function setCrm(int $crm): self
    {
        $this->crm = $crm;

        return $this;
    }

    /**
     * Get the value of name
     */ 
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */ 
    public function setName(string $name) :self
    {
        $this->name = $name;

        return $this;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'crm' => $this->getCrm(),
            'name' => $this->getName(),
            'specialty' => $this->getSpecialty()->getDescription()
        ];    
    }
}