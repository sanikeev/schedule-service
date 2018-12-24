<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CityRepository")
 */
class City
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $name;

    /**
     * @var integer
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $tripDuration;

    /**
     * @var Schedule[]
     * @ORM\OneToMany(targetEntity="Schedule", mappedBy="city")
     */
    protected $schedule;

    public function __construct()
    {
        $this->schedule = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return City
     */
    public function setName(string $name): City
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return int
     */
    public function getTripDuration(): int
    {
        return $this->tripDuration;
    }

    /**
     * @param int $tripDuration
     * @return City
     */
    public function setTripDuration(int $tripDuration): City
    {
        $this->tripDuration = $tripDuration;

        return $this;
    }

    /**
     * @return Collection|Schedule[]
     */
    public function getSchedule(): Collection
    {
        return $this->schedule;
    }

    public function addSchedule(Schedule $schedule): self
    {
        if (!$this->schedule->contains($schedule)) {
            $this->schedule[] = $schedule;
            $schedule->setCity($this);
        }

        return $this;
    }

    public function removeSchedule(Schedule $schedule): self
    {
        if ($this->schedule->contains($schedule)) {
            $this->schedule->removeElement($schedule);
            // set the owning side to null (unless already changed)
            if ($schedule->getCity() === $this) {
                $schedule->setCity(null);
            }
        }

        return $this;
    }


}
