<?php

namespace App\Entity;

use App\Repository\SpecialtyRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SpecialtyRepository::class)
 */
class Specialty implements \JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function JsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'description' => $this->getDescription(),
            '_links'=>[
                [
                    'rel'=>'self',
                    'path'=>'/especialidades/' .$this->getId()
                ],
                [
                    'rel'=>'medicos',
                    'path'=>'/especialidades/' . $this->getId() . '/medicos'
                ]
            ]
        ];
    }
}
