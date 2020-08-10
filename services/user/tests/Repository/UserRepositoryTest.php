<?php

namespace App\Tests\Repository;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserRepositoryTest extends KernelTestCase
{
    protected $email;
    protected $password;
    protected $user;

    public function setters()
    {
        $this->email = 'testUserRepositoryEmailTest@domain.com';
        $this->password = 'testUserRepositoryPasswordTest';
    }

    public function createUserMock()
    {
        self::bootKernel();
        $this->setters();
        self::$container->get(UserRepository::class)->createUser($this->email, $this->password);
    }

    public function removeUserMock()
    {
        self::bootKernel();
        $userCreated = self::$container->get(UserRepository::class)->findOneBy(['email' => $this->email]);
        $this->assertEquals($this->email, $userCreated->getEmail());
        self::$container->get(UserRepository::class)->removeUser($userCreated);
    }

    public function testUserRepositoryCreateUser()
    {
        self::bootKernel();

        $user = $this->createUserMock();
        $userCreated = self::$container->get(UserRepository::class)->findOneBy(['email' => $this->email]);
        echo "Assert Equals Email: " . $this->email . " " . $userCreated->getEmail();
        $this->assertEquals($this->email, $userCreated->getEmail());
        $this->assertNull($this->removeUserMock());
    }

    public function testUserRepositoryFindUserByEmail()
    {
        self::bootKernel();
        $user = $this->createUserMock();
        $users = self::$container->get(UserRepository::class)->FindUserByEmail($this->email);
        $this->assertEquals($this->email, $users->getEmail());
        $this->removeUserMock();
    }
}
