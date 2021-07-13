<?php
namespace App\Controller;



use App\Entity\Doctor;
use App\Helper\DoctorFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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

    public function __construct(
        EntityManagerInterface $entityManager, 
        DoctorFactory $doctorFactory
    )
    {
       $this->entityManager = $entityManager;
       $this->doctorFactory = $doctorFactory;
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
        $doctorRepository = $this
            ->getDoctrine()
            ->getRepository(Doctor::class);

        $doctorList = $doctorRepository->findAll();
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
        $doctorRepository = $this
            ->getDoctrine()
            ->getRepository(Doctor::class);
        $doctor = $doctorRepository->find($id);


        return $doctor;

    }
}
