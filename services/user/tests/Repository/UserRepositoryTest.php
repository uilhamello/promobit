<?php

namespace App\Tests\Repository;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserRepositoryTest extends KernelTestCase
{
    public function testUserRepositoryUserExist()
    {
        self::bootKernel();
        $email = 'user1@domain.com';
        $users = self::$container->get(UserRepository::class)->findOneBy(['email' => $email]);
        $this->assertEquals($email, $users->getEmail());
    }

    public function testUserRepositoryCreateUser()
    {
        self::bootKernel();
        $email = 'testUserRepositoryCreateUser4@domain.com';
        $password = '123456';
        $users = self::$container->get(UserRepository::class)->createUser($email, $password);

        $userCreated = self::$container->get(UserRepository::class)->findOneBy(['email' => $email]);
        $this->assertEquals($email, $userCreated->getEmail());

        $users = self::$container->get(UserRepository::class)->removeUser($userCreated);
    }

    public function testUserRepositoryRemoveUser()
    {
        self::bootKernel();
        $email = 'testUserRepositoryCreateUser4@domain.com';
        $password = '123456';
        $users = self::$container->get(UserRepository::class)->createUser($email, $password);

        $userCreated = self::$container->get(UserRepository::class)->findOneBy(['email' => $email]);
        $this->assertEquals($email, $userCreated->getEmail());

        $users = self::$container->get(UserRepository::class)->removeUser($userCreated);
        $this->assertNull($users);
    }
}
