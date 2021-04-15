<?php

declare(strict_types=1);

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use App\Entity\Traits\EntityIdTrait;
use App\Entity\Traits\EntityTimestampableTrait;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity("email")
 *
 * @author  Gaëtan Rolé-Dubruille <gaetan.role-dubruille@sensiolabs.com>
 */
class User implements UserInterface
{
    use EntityIdTrait;
    use EntityTimestampableTrait;

    /**
     * @var string
     * @ORM\Column(type="string", length=32)
     * @Assert\NotBlank
     * @Assert\Length(min=2, max=32)
     */
    private $firstName;

    /**
     * @var string
     * @ORM\Column(type="string", length=32)
     * @Assert\NotBlank
     * @Assert\Length(min=2, max=32)
     */
    private $lastName;

    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="date_immutable")
     */
    private $birthDate;

    /**
     * @var string
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank
     * @Assert\Email
     */
    private $email;

    /**
     * @var array
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string
     * @Assert\Length(max=4096)
     * @Assert\Regex(pattern="/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/",
     *     message="Votre mot de passe doit contenir 8 charactères dont 1 chiffre minimum."
     * )
     */
    private $plainPassword;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="date_immutable", nullable=true)
     */
    private $lastLogin;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $isActive = true;

    public function __construct()
    {
        $this->uuid = Uuid::uuid4();
        $this->setCreatedAt(new DateTimeImmutable());
    }

    public function __toString(): string
    {
        return $this->firstName.' - '.$this->lastName;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getAge(): int
    {
        $now = new DateTime();

        return $now->diff($this->birthDate)->y;
    }

    public function getBirthDate(): ?DateTimeImmutable
    {
        return $this->birthDate;
    }

    public function setBirthDate(DateTimeInterface $birthDate): self
    {
        $this->birthDate
            = $birthDate instanceof DateTime ? DateTimeImmutable::createFromMutable($birthDate) : $birthDate;

        return $this;
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
        return $this->email;
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

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt(): void
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getLastLogin(): ?DateTimeImmutable
    {
        return $this->lastLogin;
    }

    public function setLastLogin(DateTimeInterface $lastLogin): self
    {
        $this->lastLogin
            = $lastLogin instanceof DateTime ? DateTimeImmutable::createFromMutable($lastLogin) : $lastLogin;

        return $this;
    }
}
