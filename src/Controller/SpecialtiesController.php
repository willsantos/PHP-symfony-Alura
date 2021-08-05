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
     */
    public function updateEntity($entityUpdate, $id)
    {
        $entity = $this->repository->find($id);
        if(is_null($entity)){
            throw new \InvalidArgumentException();
        }

        $entity->setDescription($entityUpdate->getDescription());
    }


}
