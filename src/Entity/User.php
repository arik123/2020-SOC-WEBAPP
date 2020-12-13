<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email."
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="boolean")
     */
    private $Car;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Meno;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Priezvisko;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $CarDescription;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $profilePicture;

    /**
     * @ORM\OneToMany(targetEntity=Route::class, mappedBy="driver")
     */
    private $driver;

    /**
     * @ORM\ManyToMany(targetEntity=Route::class, mappedBy="passenger")
     */
    private $passenger;

    public function __construct()
    {
        $this->driver = new ArrayCollection();
        $this->passenger = new ArrayCollection();
    }

    public function getProfilePicture()
    {
        return $this->profilePicture;
    }

    public function setProfilePicture($profilePicture)
    {
        $this->profilePicture = $profilePicture;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getCar(): ?bool
    {
        return $this->Car;
    }

    public function setCar(bool $Car): self
    {
        $this->Car = $Car;

        return $this;
    }

    public function getMeno(): ?string
    {
        return $this->Meno;
    }

    public function setMeno(string $Meno): self
    {
        $this->Meno = $Meno;

        return $this;
    }

    public function getPriezvisko(): ?string
    {
        return $this->Priezvisko;
    }

    public function setPriezvisko(string $Priezvisko): self
    {
        $this->Priezvisko = $Priezvisko;

        return $this;
    }

    public function getCarDescription(): ?string
    {
        return $this->CarDescription;
    }

    public function setCarDescription(?string $CarDescription): self
    {
        $this->CarDescription = $CarDescription;

        return $this;
    }

    /**
     * @return Collection|Route[]
     */
    public function getDriver(): Collection
    {
        return $this->driver;
    }

    public function addRoute(Route $route): self
    {
        if (!$this->driver->contains($route)) {
            $this->driver[] = $route;
            $route->setDriver($this);
        }

        return $this;
    }

    public function removeRoute(Route $route): self
    {
        if ($this->driver->removeElement($route)) {
            // set the owning side to null (unless already changed)
            if ($route->getDriver() === $this) {
                $route->setDriver(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Route[]
     */
    public function getPassenger(): Collection
    {
        return $this->passenger;
    }

    public function addPassenger(Route $passenger): self
    {
        if (!$this->passenger->contains($passenger)) {
            $this->passenger[] = $passenger;
            $passenger->addPassenger($this);
        }

        return $this;
    }

    public function removePassenger(Route $passenger): self
    {
        if ($this->passenger->removeElement($passenger)) {
            $passenger->removePassenger($this);
        }

        return $this;
    }
}
