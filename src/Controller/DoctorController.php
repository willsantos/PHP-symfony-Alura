<?php
namespace App\Controller;

use App\Entity\Doctor;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class DoctorController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager )
    {
       $this->entityManager = $entityManager;
    }
    /**
     * @Route("/medicos", methods={"POST"})
     */

    public function create(Request $request): Response
    {
        $body = $request->getContent();
        $bodyJson = json_decode($body);

        $doctor = new Doctor();
        $doctor->crm = $bodyJson->crm;
        $doctor->name = $bodyJson->name;

        $this->entityManager->persist($doctor);
        $this->entityManager->flush();

        return new JsonResponse($doctor);
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
    public function findByOne(Request $request): Response
    {
        $id = $request->get('id');
        $doctorRepository = $this
            ->getDoctrine()
            ->getRepository(Doctor::class);
        $doctor = $doctorRepository->find($id);
        $statusCode = is_null($doctor) ? Response::HTTP_NO_CONTENT : 200;
        return new JsonResponse($doctor,$statusCode);
    }

}
