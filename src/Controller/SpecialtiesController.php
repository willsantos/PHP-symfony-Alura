<?php

namespace App\Controller;

use App\Entity\Specialty;
use App\Repository\SpecialtyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SpecialtiesController extends AbstractController 
{
    /** 
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var SpecialtyRepository 
     */
    private $specialtyRepository;


    public function __construct(
        EntityManagerInterface $entityManager,
        SpecialtyRepository $specialtyRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->specialtyRepository = $specialtyRepository;
    }

    


    /**
     * @Route("/especialidades", methods={"POST"})
     */
    public function create(Request $request): Response
    {
        $body = $request->getContent();
        $bodyJson = json_decode($body);

        $specialty = new Specialty();
        $specialty->setDescription($bodyJson->description);

        $this->entityManager->persist($specialty);
        $this->entityManager->flush();

        return new JsonResponse($specialty);
    }

    /**
     * @Route("/especialidade/{id}",methods={"PUT"})
     */
    public function update(int $id, Request $request): Response
    {
        $body = $request->getContent();
        $bodyJson = json_decode($body);

        $specialty = $this->specialtyRepository->find($id);
        $specialty->setDescription($bodyJson->description);

        $this->entityManager->flush();

        return new JsonResponse($specialty);
    }

    /**
     * @Route("/especialidade/{id}",methods={"DELETE"})
     */
    public function remove(int $id):Response
    {
        $specialty = $this->specialtyRepository->find($id);
        $this->entityManager->remove($specialty);
        $this->entityManager->flush();

        return new Response('',Response::HTTP_NO_CONTENT);
    }

    /**
     *
     * @Route("/especialidades",methods={"GET"})
     */
    public function findAll(): Response
    {
        $specialtyList = $this->specialtyRepository->findAll();

        return new JsonResponse($specialtyList);
    }

    /**
     *@Route("/especialidades/{id}",methods={"GET"})
     */
    public function findByOne(int $id): Response
    {
        return new JsonResponse($this->specialtyRepository->find($id));
    }
    
}
