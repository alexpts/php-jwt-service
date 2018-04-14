<?php
declare(strict_types=1);

use Emarref\Jwt\Encryption\EncryptionInterface;
use Emarref\Jwt\Jwt;
use Emarref\Jwt\Token;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use PTS\JwtService\JwtService;

class EncodeTest extends TestCase
{

    /**
     * @throws ReflectionException
     */
    public function testEncode(): void
    {
        $payload = ['aud' => 'site', 'exp' => time()];

        $token = $this->createMock(Token::class);
        $encryption = $this->createMock(EncryptionInterface::class);

        $lib = $this->getMockBuilder(Jwt::class)
            ->setMethods(['serialize'])
            ->disableOriginalConstructor()
            ->getMock();
        $lib->expects(self::once())->method('serialize')->with($token, $encryption)
            ->willReturn('stringToken');

        /** @var MockObject|JwtService $service */
        $service = $this->getMockBuilder(JwtService::class)
            ->setMethods(['getLib', 'createToken', 'getEncryption'])
            ->disableOriginalConstructor()
            ->getMock();
        $service->expects(self::once())->method('getLib')->willReturn($lib);
        $service->expects(self::once())->method('createToken')->with($payload)->willReturn($token);
        $service->expects(self::once())->method('getEncryption')->willReturn($encryption);

        $actual = $service->encode($payload);
        self::assertSame('stringToken', $actual);
    }
}