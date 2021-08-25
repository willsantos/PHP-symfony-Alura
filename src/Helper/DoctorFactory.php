<?php
namespace App\Helper;

use App\Entity\Doctor;
use App\Repository\SpecialtyRepository;

class DoctorFactory implements EntityFactoryInterface
{
    /**
     * @var specialtyRepository
     */
    private $specialtyRepository;

    public function __construct(SpecialtyRepository $specialtyRepository)
    {
        $this->specialtyRepository = $specialtyRepository;
    }

    public function makeEntity(string $json):Doctor
    {
        $bodyJson = json_decode($json);

        $this->checkFields($bodyJson);

        $specialtyId = $bodyJson->specialtyId;
        $specialty = $this->specialtyRepository->find($specialtyId);

        $doctor = new Doctor();
        $doctor
            ->setCrm($bodyJson->crm)
            ->setName($bodyJson->name)
            ->setSpecialty($specialty);


        return $doctor;
    }

    /**
     * @param $bodyJson
     * @throws EntityFactoryException
     */
    public function checkFields($bodyJson): void
    {
        if (!property_exists($bodyJson, 'specialtyId')) {
            throw new EntityFactoryException('specialtyId is required');
        }
        if (!property_exists($bodyJson, 'crm')) {
            throw new EntityFactoryException('CRM is required');
        }
        if (!property_exists($bodyJson, 'name')) {
            throw new EntityFactoryException('Name is required');
        }
    }
}
