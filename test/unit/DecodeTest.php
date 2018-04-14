<?php
declare(strict_types=1);

use Emarref\Jwt\Jwt;
use Emarref\Jwt\Token;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use PTS\JwtService\JwtService;

class DecodeTest extends TestCase
{

    /**
     * @throws ReflectionException
     */
    public function testDecode(): void
    {
        $token = 'stringToken';

        $lib = $this->getMockBuilder(Jwt::class)
            ->setMethods(['deserialize'])
            ->disableOriginalConstructor()
            ->getMock();
        $lib->expects(self::once())->method('deserialize')->with($token)->willReturn($this->createMock(Token::class));

        /** @var MockObject|JwtService $service */
        $service = $this->getMockBuilder(JwtService::class)
            ->setMethods(['getLib'])
            ->disableOriginalConstructor()
            ->getMock();

        $service->expects(self::once())->method('getLib')->willReturn($lib);

        $service->decode($token);
    }
}