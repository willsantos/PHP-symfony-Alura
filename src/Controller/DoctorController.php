<?php
namespace App\Controller;



use App\Entity\Doctor;
use App\Helper\DoctorFactory;
use App\Helper\RequestExtractor;
use App\Repository\DoctorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DoctorController extends BaseController
{

    public function __construct(
        EntityManagerInterface $entityManager, 
        DoctorFactory $factory,
        DoctorRepository $repository,
        RequestExtractor $extractor,
        CacheItemPoolInterface $cacheItem
    )
    {
       parent::__construct($repository,$entityManager,$factory,$extractor,$cacheItem);

    }



    /**
     * @param int $id
     * @return object | null
     */

    public function findDoctor(int $id)
    {
        $doctor = $this->repository->find($id);

        return $doctor;
    }

    /**
     * @Route("/especialidades/{idSpecialty}/medicos",methods={"GET"})
     */
    public function findBySpecialty(int $idSpecialty): Response
    {
        $doctor = $this->repository->findBy([
            "specialty"=>$idSpecialty
        ]);

        return new JsonResponse($doctor);
    }

    /**
     * @param Doctor $entityUpdate
     */
    function updateEntity($entityUpdate, $id)
    {
        $entity = $this->repository->find($id);
        if(is_null($entity)){
            throw new \InvalidArgumentException();
        }

        $entity
            ->setCrm($entityUpdate->getCrm())
            ->setName($entityUpdate->getName())
            ->setSpecialty($entityUpdate->getSpecialty());
    }

    function cachePrefix(): string
    {
        return '_doctor';
    }
}
