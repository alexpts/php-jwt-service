<?php
declare(strict_types=1);

use Emarref\Jwt\Algorithm\Hs512;
use Emarref\Jwt\Jwt;
use PHPUnit\Framework\TestCase;
use PTS\JwtService\JwtService;

class GetLibTest extends TestCase
{
    /**
     * @throws \ReflectionException
     */
    public function testGetLib(): void
    {
        $service = new JwtService(new Hs512(''));

        $method = new \ReflectionMethod(JwtService::class, 'getLib');
        $method->setAccessible(true);
        $actual = $method->invoke($service);

        self::assertInstanceOf(Jwt::class, $actual);
    }
}