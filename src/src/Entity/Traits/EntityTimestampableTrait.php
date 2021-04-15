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

namespace App\Entity\Traits;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * This entity trait is useful to keep dates information or useful for:
 *
 * @see     https://packagist.org/packages/gedmo/doctrine-extensions.
 * @see     https://github.com/Atlantic18/DoctrineExtensions/blob/master/doc/timestampable.md
 *
 * You can also use MappedSuperclass but not recommended.
 * @see     https://www.doctrine-project.org/projects/doctrine-orm/en/latest/reference/inheritance-mapping.html
 * @see     https://medium.com/@galopintitouan/using-traits-to-compose-your-doctrine-entities-9b516335119b
 *
 * @author  Gaëtan Rolé-Dubruille <gaetan.role-dubruille@sensiolabs.com>
 */
trait EntityTimestampableTrait
{
    /**
     * @var DateTimeImmutable
     *
     * @ORM\Column(type="datetime_immutable")
     */
    protected $createdAt;

    /**
     * @var DateTimeImmutable|null
     *
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    protected $updatedAt;

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): void
    {
        $this->createdAt
            = $createdAt instanceof DateTime ? DateTimeImmutable::createFromMutable($createdAt) : $createdAt;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTimeInterface $updatedAt): void
    {
        $this->updatedAt
            = $updatedAt instanceof DateTime ? DateTimeImmutable::createFromMutable($updatedAt) : $updatedAt;
    }
}
