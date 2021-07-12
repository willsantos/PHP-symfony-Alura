<?php
namespace App\Controller;

use App\Entity\Doctor;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DoctorController
{
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

        return new JsonResponse($doctor);
    }
}