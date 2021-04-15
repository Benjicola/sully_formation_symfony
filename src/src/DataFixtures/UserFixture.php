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

namespace App\DataFixtures;

use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @see     https://symfony.com/doc/master/bundles/DoctrineFixturesBundle/index.html
 *
 * @author  Gaëtan Rolé-Dubruille <gaetan.role-dubruille@sensiolabs.com>
 */
final class UserFixture extends Fixture
{
    /** @var UserPasswordEncoderInterface */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * Load 4 types of User with different roles and ages in DB.
     *
     * @throws Exception Datetime Exception
     */
    public function load(ObjectManager $manager): void
    {
        foreach ($this->provideUsers() as [$firstname, $lastname, $birthDate, $email, $roles, $plainPassword]) {
            $user = new User();

            $user->setFirstName($firstname)
                ->setLastName($lastname)
                ->setBirthDate($birthDate)
                ->setEmail($email)
                ->setRoles([$roles])
                ->setPassword($this->passwordEncoder->encodePassword($user, $plainPassword))
                ->setIsActive(true)
            ;

            $manager->persist($user);
        }

        $manager->flush();
    }

    /**
     * @throws Exception Datetime Exception
     */
    private function provideUsers(): array
    {
        $junior = [
            'User Junior', 'Doe', new DateTimeImmutable('2015-01-01'),
            'junior@sensiotv.fr', 'ROLE_USER', 'junior',
        ];

        $senior = [
            'User Senior', 'Doe', new DateTimeImmutable('1955-03-10'),
            'senior@sensiotv.fr', 'ROLE_USER', 'senior',
        ];

        $admin = [
            'Admin', 'Doe', new DateTimeImmutable('1990-01-01'),
            'admin@sensiotv.fr', 'ROLE_ADMIN', 'admin',
        ];

        $superAdmin = [
            'Super Admin', 'Doe', new DateTimeImmutable('1990-01-01'),
            'superadmin@sensiotv.fr', 'ROLE_SUPER_ADMIN', 'superadmin',
        ];

        return [$junior, $senior, $admin, $superAdmin];
    }
}
