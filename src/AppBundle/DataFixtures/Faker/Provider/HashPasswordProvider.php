<?php
namespace App\AppBundle\DataFixtures\Faker\Provider;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class HashPasswordProvider
{
    /**
     * @var UserPasswordHasherInterface
     */
    private UserPasswordHasherInterface $encoder;
    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->encoder = $hasher;
    }

    public function hashPassword(string $plainPassword): string
    {
        return $this->encoder->hashPassword(new User(), $plainPassword);
    }

}