<?php

namespace App\Entity;

use App\Repository\RouteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @ORM\Column(type="geometry", options={"geometry_type"="POINT", "srid"=4326})
     */
    private $source;

    /**
     * @ORM\Column(type="geometry", options={"geometry_type"="POINT", "srid"=4326})
     */
    private $target;

    /**
     * @ORM\Column(type="geometry", options={"geometry_type"="MULTILINESTRING", "srid"=4326}, nullable=true)
     */
    private $way;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="routes")
     * @ORM\JoinColumn(name="driver_id", referencedColumnName="id", nullable=false)
     */
    private $driver;

    /**
     * @ORM\Column(type="bigint")
     * @Assert\GreaterThan(0)
     */
    private $seats;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="passenger")
     * @ORM\JoinTable(name="user_route_passenger")
     */
    private $passenger;

    /**
     * @ORM\Column(type="datetime")
     */
    private $Time;

    /**
     * @ORM\Column(type="float")
     */
    private $zachadzka;

    /**
     * 0 - no repeat
     * 1 - weekly
     * 3 - daily
     * 2 - workday
     * @ORM\Column(type="smallint")
     */
    private $repeat;

    public function __construct()
    {
        $this->passenger = new ArrayCollection();
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
    public function getPassenger(): Collection
    {
        return $this->passenger;
    }

    public function addPassenger(User $passenger): self
    {
        if (!$this->passenger->contains($passenger)) {
            $this->passenger[] = $passenger;
        }

        return $this;
    }

    public function removePassenger(User $passenger): self
    {
        $this->passenger->removeElement($passenger);

        return $this;
    }

    public function getTime(): ?\DateTimeInterface
    {
        return $this->Time;
    }

    public function setTime(\DateTimeInterface $Time): self
    {
        $this->Time = $Time;

        return $this;
    }

    public function getZachadzka(): ?float
    {
        return $this->zachadzka;
    }

    public function setZachadzka(float $zachadzka): self
    {
        $this->zachadzka = $zachadzka;

        return $this;
    }

    public function getRepeat(): ?int
    {
        return $this->repeat;
    }

    public function setRepeat(int $repeat): self
    {
        $this->repeat = $repeat;

        return $this;
    }
}
