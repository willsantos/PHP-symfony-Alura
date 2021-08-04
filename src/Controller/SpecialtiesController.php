<?php

namespace App\Controller;

use App\Entity\Specialty;
use App\Helper\RequestExtractor;
use App\Helper\SpecialtyFactory;
use App\Repository\SpecialtyRepository;
use Doctrine\ORM\EntityManagerInterface;

class SpecialtiesController extends BaseController
{



    public function __construct(
        EntityManagerInterface $entityManager,
        SpecialtyRepository $repository,
        SpecialtyFactory $factory,
        RequestExtractor $extractor
    )
    {
        parent::__construct($repository,$entityManager,$factory,$extractor);
    }

    /**
     * @param Specialty $entityUpdate
     * @param Specialty $entity
     */
    public function updateEntity($entityUpdate, $entity)
    {
        $entity->setDescription($entityUpdate->getDescription());
    }


}
