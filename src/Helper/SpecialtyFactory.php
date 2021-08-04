<?php


namespace App\Helper;


use App\Entity\Specialty;

class SpecialtyFactory implements EntityFactoryInterface
{
    public function makeEntity(string $json): Specialty
    {
        $data = json_decode($json);
        $specialty = new Specialty();
        $specialty->setDescription($data->description);

        return $specialty;
    }
}