<?php
namespace App\Helper;

use App\Entity\Doctor;

class DoctorFactory
{
    public function makeDoctor(string $json):Doctor
    {
        $bodyJson = json_decode($json);

        $doctor = new Doctor();
        $doctor->crm = $bodyJson->crm;
        $doctor->name = $bodyJson->name;

        return $doctor;
    }
}
