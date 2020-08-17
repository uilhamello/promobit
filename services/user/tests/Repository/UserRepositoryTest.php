<?php

namespace App\Tests\Repository;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
        return self::$container->get(UserRepository::class)->createUser($this->email, $this->password)['data'];
    }

    public function removeUserMock()
    {
        self::bootKernel();
        self::$container->get(UserRepository::class)->removeUser($this->email);
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


    public function testUserRepositoryUpdateUser()
    {
        self::bootKernel();
        $this->setters();
        $user = $this->createUserMock();
        $newEmail = $this->email . 'updated';
        $newPassword = $this->password . 'updated';

        $userCreated = self::$container->get(UserRepository::class)->updateUser(['email' => $this->email, 'new' => ['email' => $newEmail, 'password' => $newPassword]]);

        $this->assertEquals($userCreated['status'], 'success');

        $this->assertNotEquals($this->email, $userCreated['data']->getEmail());
        $realEmail = $this->email;
        $this->email = $newEmail;
        $this->assertNull($this->removeUserMock());
        $this->email = $realEmail;
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
