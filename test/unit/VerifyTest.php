<?php
declare(strict_types=1);

use Emarref\Jwt\Encryption\EncryptionInterface;
use Emarref\Jwt\Exception\InvalidIssuerException;
use Emarref\Jwt\Jwt;
use Emarref\Jwt\Token;
use Emarref\Jwt\Verification\Context;
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
     */
    public function testVerify(?string $audience): void
    {
        $token = $this->createMock(Token::class);
        $verificationContext = $this->getMockBuilder(Context::class)
            ->disableOriginalConstructor()
            ->setMethods(['setEncryption', 'setAudience'])
            ->getMock();
        $verificationContext->expects(self::once())->method('setEncryption')->willReturnSelf();
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


        $service = $this->getMockBuilder(JwtService::class)
            ->setMethods(['getAudience', 'getLib', 'getEncryption'])
            ->disableOriginalConstructor()
            ->getMock();
        $service->expects(self::exactly($audience ? 2 : 1))->method('getAudience')->willReturn($audience);
        $service->expects(self::once())->method('getEncryption')
            ->willReturn($this->createMock(EncryptionInterface::class));
        $service->expects(self::once())->method('getLib')->willReturn($lib);

        $service->verify($token, $verificationContext);
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