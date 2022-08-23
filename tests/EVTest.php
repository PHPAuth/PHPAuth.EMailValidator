<?php

use PHPUnit\Framework\TestCase;

class EVTest extends TestCase
{
    public static $validator;

    public static function setUpBeforeClass()
    {
        require_once __DIR__ . '/../vendor/autoload.php';
        self::$validator = new \PHPAuth\EMailValidator();
    }

    public function testEmails()
    {
        $validator = self::$validator;
        
        // Successful check
        $this->assertTrue($validator::isValid('arris@mail.ru'));

        // Successful check
        $this->assertTrue($validator::isValid('test@gmail.com'));

        // Failed check
        $this->assertFalse($validator::isValid('foo@0d00.com'));

        // Failed check
        $this->assertFalse($validator::isValid('xxxx'));

        // Failed check
        $this->assertFalse($validator::isValid('foo@b-response.com'));
    }


}