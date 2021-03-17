<?php

namespace App\Entity;

use App\Repository\SlugRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SlugRepository::class)
 */
class Slug
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $slug_name;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $entity_name;

    /**
     * @ORM\Column(type="integer")
     */
    private $entity_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSlugName(): ?string
    {
        return $this->slug_name;
    }

    public function setSlugName(string $slug_name): self
    {
        $this->slug_name = $slug_name;

        return $this;
    }

    public function getEntityName(): ?string
    {
        return $this->entity_name;
    }

    public function setEntityName(string $entity_name): self
    {
        $this->entity_name = $entity_name;

        return $this;
    }

    public function getEntityId(): ?int
    {
        return $this->entity_id;
    }

    public function setEntityId(int $entity_id): self
    {
        $this->entity_id = $entity_id;

        return $this;
    }
}
