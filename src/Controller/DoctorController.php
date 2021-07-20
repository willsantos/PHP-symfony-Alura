<?php
namespace App\Controller;



use App\Helper\DoctorFactory;
use App\Repository\DoctorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DoctorController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var DoctorFactory
     */
    private $doctorFactory;
    /**
     * @var DoctorRepository
     */
    private $repository;

    public function __construct(
        EntityManagerInterface $entityManager, 
        DoctorFactory $doctorFactory,
        DoctorRepository $repository
    )
    {
       $this->entityManager = $entityManager;
       $this->doctorFactory = $doctorFactory;
        $this->repository = $repository;
    }
    /**
     * @Route("/medicos", methods={"POST"})
     */

    public function create(Request $request): Response
    {
        $body = $request->getContent();
        $doctor= $this->doctorFactory->makeDoctor($body);

        $this->entityManager->persist($doctor);
        $this->entityManager->flush();

        return new JsonResponse($doctor);
    }

    /**
     * @Route("/medicos/{id}",methods={"PUT"})
     */

    public function update(int $id, Request $request): Response
    {
        $body = $request->getContent();
        $doctorUpdate = $this->doctorFactory->makeDoctor($body);

       
        
        $doctor = $this->findDoctor($id);
       

        if(is_null($doctor)){
            return new Response('',Response::HTTP_NOT_FOUND);
        }

        $doctor
            ->setCrm($doctorUpdate->getCrm)
            ->setName($doctorUpdate->getName);


        $this->entityManager->flush();

        return new JsonResponse($doctor);
    }

    /**
     * @Route("/medicos/{id}",methods={"DELETE"})
     */

     public function delete(int $id):Response
     {
        $doctor = $this->findDoctor($id);

        if(is_null($doctor)){
            return new Response('Médico não encontrado',Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($doctor);
        $this->entityManager->flush();

        return new Response('',Response::HTTP_NO_CONTENT);
     }

    /**
     * @Route("/medicos",methods={"GET"})
     */

    public function findAll():Response
    {
        $doctorList = $this->repository->findAll();
        return new JsonResponse($doctorList);
    }

    /**
     * @Route("/medicos/{id}",methods={"GET"})
     */
    public function findByOne($id): Response
    {
        
        $doctor = $this->findDoctor($id);
        $statusCode = is_null($doctor) ? Response::HTTP_NO_CONTENT : 200;
        return new JsonResponse($doctor,$statusCode);
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
}
