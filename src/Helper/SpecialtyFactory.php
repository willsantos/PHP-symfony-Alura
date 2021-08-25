<?php


namespace App\Helper;


use App\Entity\Specialty;

class SpecialtyFactory implements EntityFactoryInterface
{
    public function makeEntity(string $json): Specialty
    {
        $data = json_decode($json);

        $this->checkFields($data);

        $specialty = new Specialty();
        $specialty->setDescription($data->description);

        return $specialty;
    }

    /**
     * @param $data
     * @throws EntityFactoryException
     */
    private function checkFields($data): void
    {
        if (!property_exists($data, 'description')) {
            throw new EntityFactoryException('Description is required');
        }
    }
}