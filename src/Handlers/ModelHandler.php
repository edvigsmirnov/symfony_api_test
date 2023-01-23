<?php

namespace App\Handlers;

use App\Entity\Product\Model;
use Doctrine\ORM\EntityManagerInterface;

class ModelHandler
{
    /**
     * @param string $name
     * @param EntityManagerInterface $em
     * @return Model[]
     */
    public function getModelsByName(string $name, EntityManagerInterface $em): array
    {
        return $em->getRepository(Model::class)->findBy(['name' => $name]);
    }

    /**
     * @param int $modelId
     * @param EntityManagerInterface $em
     * @return Model
     */
    public function getModelByModelId(int $modelId, EntityManagerInterface $em): Model
    {
        return $em->getRepository(Model::class)->findOneBy(['id' => $modelId]);
    }
}