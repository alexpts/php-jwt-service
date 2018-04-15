<?php
declare(strict_types=1);

use Emarref\Jwt\Algorithm\Hs512;
use Emarref\Jwt\Claim;
use Emarref\Jwt\Jwt;
use Emarref\Jwt\Encryption;
use Emarref\Jwt\Token;
use PHPUnit\Framework\TestCase;
use PTS\JwtService\JwtService;

class ConstructTest extends TestCase
{

    /**
     * @throws \ReflectionException
     */
    public function testConstructor(): void
    {
        $secret = '#SKJ4nhinjk23l;k40';
        $alg = new Hs512($secret);

        $service = new JwtService($alg);

        $prop = new \ReflectionProperty(JwtService::class, 'claimFactory');
        $prop2 = new \ReflectionProperty(JwtService::class, 'lib');
        $prop3 = new \ReflectionProperty(JwtService::class, 'encryption');
        $prop4 = new \ReflectionProperty(JwtService::class, 'emptyToken');

        $prop->setAccessible(true);
        self::assertInstanceOf(Claim\Factory::class, $prop->getValue($service));

        $prop2->setAccessible(true);
        self::assertInstanceOf(Jwt::class, $prop2->getValue($service));

        $prop3->setAccessible(true);
        self::assertInstanceOf(Encryption\EncryptionInterface::class, $prop3->getValue($service));

        $prop4->setAccessible(true);
        self::assertInstanceOf(Token::class, $prop4->getValue($service));
    }

}