<?php
declare(strict_types=1);

use Emarref\Jwt\Claim\ClaimInterface;
use Emarref\Jwt\Claim\Factory;
use Emarref\Jwt\Token;
use PHPUnit\Framework\TestCase;
use PTS\JwtService\JwtService;

class SetPayloadClaimTest extends TestCase
{

    /**
     * @param array $payload
     *
     * @dataProvider dataProvider
     * @throws ReflectionException
     */
    public function testSetPayloadClaim(array $payload): void
    {
        $count = count($payload);
        $keys = array_map(function(string $key) {
            return [$key];
        }, array_keys($payload));

        $values = array_map(function(string $value) {
            return [$value];
        }, array_values($payload));

        $token = $this->getMockBuilder(Token::class)
            ->disableOriginalConstructor()
            ->setMethods(['addClaim'])
            ->getMock();
        $token->expects(self::exactly($count))->method('addClaim');

        $claim = $this->getMockBuilder(ClaimInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['setValue'])
            ->getMockForAbstractClass();
        $claim->expects(self::exactly($count))->method('setValue')->withConsecutive(...$values);

        $claimFactory = $this->getMockBuilder(Factory::class)
            ->disableOriginalConstructor()
            ->setMethods(['get'])
            ->getMock();
        $claimFactory->expects(self::exactly($count))->method('get')
            ->withConsecutive(...$keys)->willReturn($claim);

        $service = $this->getMockBuilder(JwtService::class)
            ->disableOriginalConstructor()
            ->setMethods(['getClaimFactory'])
            ->getMock();
        $service->expects(self::once())->method('getClaimFactory')->willReturn($claimFactory);


        $method = new ReflectionMethod(JwtService::class, 'setPayloadClaim');
        $method->setAccessible(true);
        $method->invoke($service, $payload, $token);
    }

    public function dataProvider(): array
    {
        return [
            [[
                'aud' => 'site',
                'exp' => time(),
            ]],
            [[
                'aud' => 'wwww',
            ]],
            [[
                'aud' => 'service_1',
                'exp' => time(),
                'pubKey' => 'value,'
            ]],
        ];
    }
}