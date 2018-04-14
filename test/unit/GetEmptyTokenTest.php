<?php
declare(strict_types=1);

use Emarref\Jwt\Algorithm\Hs512;
use Emarref\Jwt\Claim;
use Emarref\Jwt\Jwt;
use Emarref\Jwt\Token;
use PHPUnit\Framework\TestCase;
use PTS\JwtService\JwtService;

class GetEmptyTokenTest extends TestCase
{
    /**
     * @throws \ReflectionException
     */
    public function testGetEmptyToken(): void
    {
        /** @var Claim\Factory $claimFactory */
        $claimFactory = $this->createMock(Claim\Factory::class);
        $service = new JwtService(new Hs512(''), new Jwt, $claimFactory);

        $method = new \ReflectionMethod(JwtService::class, 'getEmptyToken');
        $method->setAccessible(true);
        $actual = $method->invoke($service);

        self::assertInstanceOf(Token::class, $actual);
    }
}