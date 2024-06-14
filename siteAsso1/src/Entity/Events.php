<?php

namespace App\Entity;

use App\Repository\EventsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventsRepository::class)]
class Events
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255)]
    private ?string $city = null;

    #[ORM\Column(length: 255)]
    private ?string $location = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $participants = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $volunteer = null;

    /**
     * @var Collection<int, Volunteers>
     */
    #[ORM\ManyToMany(targetEntity: Volunteers::class, mappedBy: 'events')]
    private Collection $volunteers;

    public function __construct()
    {
        $this->volunteers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): static
    {
        $this->location = $location;

        return $this;
    }

    public function getParticipants(): ?string
    {
        return $this->participants;
    }

    public function setParticipants(?string $participants): static
    {
        $this->participants = $participants;

        return $this;
    }

    public function getVolunteer(): ?string
    {
        return $this->volunteer;
    }

    public function setVolunteer(?string $volunteer): static
    {
        $this->volunteer = $volunteer;

        return $this;
    }

    /**
     * @return Collection<int, Volunteers>
     */
    public function getVolunteers(): Collection
    {
        return $this->volunteers;
    }

    public function addVolunteer(Volunteers $volunteer): static
    {
        if (!$this->volunteers->contains($volunteer)) {
            $this->volunteers->add($volunteer);
            $volunteer->addEvent($this);
        }

        return $this;
    }

    public function removeVolunteer(Volunteers $volunteer): static
    {
        if ($this->volunteers->removeElement($volunteer)) {
            $volunteer->removeEvent($this);
        }

        return $this;
    }
}
