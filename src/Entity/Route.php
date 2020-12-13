<?php

namespace App\Entity;

use App\Repository\RouteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RouteRepository::class)
 */
class Route
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="geometry")
     */
    private $source;

    /**
     * @ORM\Column(type="geometry")
     */
    private $target;

    /**
     * @ORM\Column(type="geometry")
     */
    private $way;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="routes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $driver;

    /**
     * @ORM\Column(type="bigint")
     */
    private $seats;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="passengers")
     */
    private $passengers;

    public function __construct()
    {
        $this->passengers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSource()
    {
        return $this->source;
    }

    public function setSource($source): self
    {
        $this->source = $source;

        return $this;
    }

    public function getTarget()
    {
        return $this->target;
    }

    public function setTarget($target): self
    {
        $this->target = $target;

        return $this;
    }

    public function getWay()
    {
        return $this->way;
    }

    public function setWay($way): self
    {
        $this->way = $way;

        return $this;
    }

    public function getDriver(): ?User
    {
        return $this->driver;
    }

    public function setDriver(?User $driver): self
    {
        $this->driver = $driver;

        return $this;
    }

    public function getSeats(): ?string
    {
        return $this->seats;
    }

    public function setSeats(string $seats): self
    {
        $this->seats = $seats;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getPassengers(): Collection
    {
        return $this->passengers;
    }

    public function addPassengers(User $passengers): self
    {
        if (!$this->passengers->contains($passengers)) {
            $this->passengers[] = $passengers;
        }

        return $this;
    }

    public function removePassengers(User $passengers): self
    {
        $this->passengers->removeElement($passengers);

        return $this;
    }
}
