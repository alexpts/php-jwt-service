<?php
declare(strict_types=1);

use Emarref\Jwt\Encryption\EncryptionInterface;
use Emarref\Jwt\Exception\InvalidIssuerException;
use Emarref\Jwt\Jwt;
use Emarref\Jwt\Token;
use Emarref\Jwt\Verification\Context;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use PTS\JwtService\JwtService;
use PTS\JwtService\VerifyTokenException;

class VerifyTest extends TestCase
{
    /**
     * @param null|string $audience
     *
     * @throws ReflectionException
     *
     * @dataProvider verifyDataProvider
     * @throws VerifyTokenException
     */
    public function testVerify(?string $audience): void
    {
        $token = $this->createMock(Token::class);
        $verificationContext = $this->getMockBuilder(Context::class)
            ->disableOriginalConstructor()
            ->setMethods(['setAudience'])
            ->getMock();
        $verificationContext->expects(self::exactly($audience ? 1 : 0))->method('setAudience')->willReturnSelf();

        $lib = $this->getMockBuilder(Jwt::class)
            ->setMethods(['verify'])
            ->disableOriginalConstructor()
            ->getMock();

        if ($audience) {
            $this->expectException(VerifyTokenException::class);
            $this->expectExceptionMessage('Token did not pass the verification');
            $lib->expects(self::once())->method('verify')->with($token, $verificationContext)
                ->willThrowException(new InvalidIssuerException);
        }

        /** @var MockObject|JwtService $service */
        $service = $this->getMockBuilder(JwtService::class)
            ->setMethods(['getAudience', 'getLib'])
            ->disableOriginalConstructor()
            ->getMock();
        $service->expects(self::exactly($audience ? 2 : 1))->method('getAudience')->willReturn($audience);
        $service->expects(self::once())->method('getLib')->willReturn($lib);

        $service->verify($token, $verificationContext);
    }

    /**
     * @param null|string $audience
     *
     * @throws ReflectionException
     *
     * @dataProvider verifyDataProvider
     * @throws VerifyTokenException
     */
    public function testVerifyWithoutContext(?string $audience): void
    {
        $token = $this->createMock(Token::class);
        $lib = $this->getMockBuilder(Jwt::class)
            ->setMethods(['verify'])
            ->disableOriginalConstructor()
            ->getMock();

        if ($audience) {
            $this->expectException(VerifyTokenException::class);
            $this->expectExceptionMessage('Token did not pass the verification');
            $lib->expects(self::once())->method('verify')->with($token)
                ->willThrowException(new InvalidIssuerException);
        }

        /** @var MockObject|JwtService  $service */
        $service = $this->getMockBuilder(JwtService::class)
            ->setMethods(['getAudience', 'getLib', 'getEncryption'])
            ->disableOriginalConstructor()
            ->getMock();
        $service->expects(self::exactly($audience ? 2 : 1))->method('getAudience')->willReturn($audience);
        $service->expects(self::once())->method('getEncryption')
            ->willReturn($this->createMock(EncryptionInterface::class));
        $service->expects(self::once())->method('getLib')->willReturn($lib);

        $service->verify($token);
    }

    public function verifyDataProvider(): array
    {
        return [
            [null],
            ['site'],
            ['service_1'],
        ];
    }
}