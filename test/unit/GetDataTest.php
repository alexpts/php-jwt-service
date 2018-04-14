<?php
declare(strict_types=1);

use Emarref\Jwt\Algorithm\Hs512;
use Emarref\Jwt\Claim;
use Emarref\Jwt\Jwt;
use Emarref\Jwt\Token;
use Emarref\Jwt\Token\PropertyInterface;
use Emarref\Jwt\Token\PropertyList;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use PTS\JwtService\JwtService;

class GetDataTest extends TestCase
{

    /**
     * @throws \ReflectionException
     */
    public function testGetData(): void
    {
        /** @var Claim\Factory $claimFactory */
        $claimFactory = $this->createMock(Claim\Factory::class);
        $service = new JwtService(new Hs512(''), new Jwt, $claimFactory);

        /** @var PropertyInterface|MockObject $property */
        $property = $this->getMockBuilder(PropertyInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['getName', 'getValue'])
            ->getMockForAbstractClass();
        $property->expects(self::exactly(2))->method('getName')->willReturnOnConsecutiveCalls('uid', 'aud');
        $property->expects(self::exactly(2))->method('getValue')->willReturnOnConsecutiveCalls(1, 'site');

        $properties = $this->getMockBuilder(PropertyList::class)
            ->disableOriginalConstructor()
            ->setMethods(['getIterator'])
            ->getMock();
        $properties->expects(self::once())->method('getIterator')->willReturn([$property, $property]);

        $payload = $this->getMockBuilder(Token::class)
            ->disableOriginalConstructor()
            ->setMethods(['getClaims'])
            ->getMock();
        $payload->expects(self::once())->method('getClaims')->willReturn($properties);

        $token = $this->getMockBuilder(Token::class)
            ->disableOriginalConstructor()
            ->setMethods(['getPayload'])
            ->getMock();
        $token->expects(self::once())->method('getPayload')->willReturn($payload);

        $actual = $service->getData($token);
        self::assertSame([
            'uid' => 1,
            'aud' => 'site'
        ], $actual);
    }
}