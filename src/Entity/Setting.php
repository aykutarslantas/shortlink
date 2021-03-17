<?php

namespace App\Entity;

use App\Repository\SettingRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SettingRepository::class)
 */
class Setting
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $logo;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $beforefooter;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $adstand;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    public function getBeforefooter(): ?string
    {
        return $this->beforefooter;
    }

    public function setBeforefooter(?string $beforefooter): self
    {
        $this->beforefooter = $beforefooter;

        return $this;
    }

    public function getAdstand(): ?string
    {
        return $this->adstand;
    }

    public function setAdstand(?string $adstand): self
    {
        $this->adstand = $adstand;

        return $this;
    }
}
