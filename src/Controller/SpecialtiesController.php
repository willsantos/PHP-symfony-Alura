<?php

namespace App\Controller;

use App\Entity\Specialty;
use App\Helper\RequestExtractor;
use App\Helper\SpecialtyFactory;
use App\Repository\SpecialtyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SpecialtiesController extends BaseController
{

    public function __construct(
        EntityManagerInterface $entityManager,
        SpecialtyRepository $repository,
        SpecialtyFactory $factory,
        RequestExtractor $extractor,
        CacheItemPoolInterface $cacheItem,
        LoggerInterface $logger
    )
    {
        parent::__construct($repository,$entityManager,$factory,$extractor,$cacheItem,$logger);
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


    function cachePrefix(): string
    {
        return 'specialty_';
    }


    /**
     * @Route("/especialidades_html")
     */
    public function SpecialtyHtml(): Response
    {
        $specialties = $this->repository->findAll();

        return $this->render('specialties.html.twig',['specialties'=>$specialties]);
    }
}
