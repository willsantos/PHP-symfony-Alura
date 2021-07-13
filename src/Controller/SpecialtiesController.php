<?php

namespace App\Controller;

use App\Entity\Specialty;
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

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
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

    
}
