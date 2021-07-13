<?php
namespace App\Helper;

use App\Entity\Doctor;
use App\Repository\SpecialtyRepository;

class DoctorFactory
{
    /**
     * @var specialtyRepository
     */
    private $specialtyRepository;

    public function __construct(SpecialtyRepository $specialtyRepository)
    {
        $this->specialtyRepository = $specialtyRepository;
    }

    public function makeDoctor(string $json):Doctor
    {
        $bodyJson = json_decode($json);

        $specialtyId = $bodyJson->specialtyId;
        $specialty = $this->specialtyRepository->find($specialtyId);

        $doctor = new Doctor();
        $doctor
            ->setCrm($bodyJson->crm)
            ->setName($bodyJson->name)
            ->setSpecialty($specialty);


        return $doctor;
    }
}
