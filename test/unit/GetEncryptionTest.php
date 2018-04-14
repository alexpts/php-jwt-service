<?php
declare(strict_types=1);

use Emarref\Jwt\Algorithm\Hs512;
use Emarref\Jwt\Claim;
use Emarref\Jwt\Encryption\Symmetric;
use Emarref\Jwt\Jwt;
use PHPUnit\Framework\TestCase;
use PTS\JwtService\JwtService;

class GetEncryptionTest extends TestCase
{

    /**
     * @throws \ReflectionException
     */
    public function testSetAudience(): void
    {
        /** @var Claim\Factory $claimFactory */
        $claimFactory = $this->createMock(Claim\Factory::class);
        $service = new JwtService(new Hs512(''), new Jwt, $claimFactory);

        $actual = $service->getEncryption();
        self::assertInstanceOf(Symmetric::class, $actual);
    }
}