<?php

namespace App\Tests\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{

    public function restStructure(string $url, array $data)
    {
        $client = static::createClient();
        $client->request(
            'POST',
            $url,
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_REFERER' => '',
            ],
            json_encode($data)
        );
    }

    public function testUserControllerApiRegister()
    {
        $this->restStructure('/api/user/register', ['email' => 'testUserControllerApiRegisterEmail', 'password' => 'testUserControllerApiRegisterPassword']);

        $this->assertResponseIsSuccessful();
    }

    public function testUserControllerApiRegisterWithEmailEmpty()
    {
        $this->restStructure('/api/user/register', ['email' => '', 'password' => 'testUserControllerApiRegisterPassword']);

        $this->assertResponseIsSuccessful();
    }

    public function testUserControllerApiRegisterWithPasswordEmpty()
    {
        $this->restStructure('/api/user/register', ['email' => 'testUserControllerApiRegisterEmail', '' => '']);
        $this->assertResponseIsSuccessful();
    }

    public function testUserControllerApiRegisterRemoveUser()
    {
        $this->restStructure('/api/user/remove', ['email' => 'tsestUserControllerApiRegisterEmail']);
        $this->assertResponseIsSuccessful();
    }

    // public function testUserControllerApiRegisterRemoveUser()
    // {
    //     $this->restStructure('/api/user/upate', ['email' => $this->email, 'new' => ['email' => $newEmail, 'password' => $newPassword]]);
    //     $this->assertResponseIsSuccessful();
    // }
}
