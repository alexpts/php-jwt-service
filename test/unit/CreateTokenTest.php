<?php
declare(strict_types=1);

use Emarref\Jwt\Token;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use PTS\JwtService\JwtService;

class CreateTokenTest extends TestCase
{
    /**
     * @param int $countClaim
     * @param int $expire
     * @param string|null $audience
     *
     * @dataProvider createTokenDataProvider
     */
    public function testCreateToken(int $countClaim, int $expire, string $audience = null): void
    {
        $token = $this->getMockBuilder(Token::class)
            ->disableOriginalConstructor()
            ->setMethods(['addClaim'])
            ->getMock();
        $token->expects(self::exactly($countClaim))->method('addClaim');

        /** @var JwtService|MockObject $service */
        $service = $this->getMockBuilder(JwtService::class)
            ->disableOriginalConstructor()
            ->setMethods(['getEmptyToken', 'setPayloadClaim'])
            ->getMock();
        $service->expects(self::once())->method('getEmptyToken')->willReturn($token);

        if ($expire) {
            $service->setExpire($expire);
        }

        if ($audience) {
            $service->setAudience($audience);
        }

        $service->createToken([]);
    }

    public function createTokenDataProvider(): array
    {
        return [
            [2, 0, null],
            [3, 100, null],
            [3, 0, 'site'],
            [4, 200, 'service_1'],
        ];
    }
}