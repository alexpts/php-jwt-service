<?php
declare(strict_types=1);

use Emarref\Jwt\Algorithm\Hs512;
use Emarref\Jwt\Claim;
use Emarref\Jwt\Jwt;
use PHPUnit\Framework\TestCase;
use PTS\JwtService\JwtService;

class GetClaimFactoryTest extends TestCase
{
    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \InvalidArgumentException
     * @throws \ReflectionException
     */
    public function testGetClaimFactory(): void
    {
        $service = new JwtService(new Hs512(''));

        $method = new \ReflectionMethod(JwtService::class, 'getClaimFactory');
        $method->setAccessible(true);
        $actual = $method->invoke($service);

        self::assertInstanceOf(Claim\Factory::class, $actual);
    }
}