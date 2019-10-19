<?php

namespace App\Tests\Entity;

use PHPUnit\Framework\TestCase;
use App\Entity\User;

class UserTest extends TestCase
{
    public function testSetByToken()
    {
        $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6MSwiZ29vZ2xlSWQiOiIxMTYyMjkzNzYzMTE5OTU2Mzk0NCIsImVtYWlsIjoiZW1haWxAbXVzdGFyZC5pbyIsImVtYWlsVmVyaWZpZWQiOnRydWUsIm5hbWUiOiJTb21lb25lIE5pY2tuYW1lIiwiZ2l2ZW5OYW1lIjoiU29tZW9uZSIsImZhbWlseU5hbWUiOiJOaWNrbmFtZSIsInBpY3R1cmUiOiJodHRwczovL2xoMy5nb29nbGV1c2VyY29udGVudC5jb20vYS0va2V5IiwibG9jYWxlIjoicHQtQlIiLCJhY2Nlc3NUb2tlbiI6IjVkYWI2ODU5ODYzMWM1ZGFiNjg1OTg2MzFlNWRhYjY4NTk4NjMxZjVkYWI2ODU5ODYzMjA1ZGFiNjg1OTg2MzIxIn0.zRF7SjcNdH5AUxOOO12rC1Ra4E0IvtLzLdMyP0tkwzw';
        $user = new User();
        $user->setByToken($token);

        $this->assertEquals($user->getGoogleId(), '11622937631199563944');
        $this->assertEquals($user->getId(), 1);
        $this->assertEquals($user->getEmail(), 'email@mustard.io');
        $this->assertEquals($user->getEmailVerified(), true);
        $this->assertEquals($user->getName(), 'Someone Nickname');
        $this->assertEquals($user->getGivenName(), 'Someone');
        $this->assertEquals($user->getFamilyName(), 'Nickname');
        $this->assertEquals($user->getPicture(), 'https://lh3.googleusercontent.com/a-/key');
        $this->assertEquals($user->getLocale(), 'pt-BR');
    }
}
