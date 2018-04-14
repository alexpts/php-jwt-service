<?php
declare(strict_types=1);

use Emarref\Jwt\Algorithm\Hs512;
use Emarref\Jwt\Claim;
use Emarref\Jwt\Jwt;
use PHPUnit\Framework\TestCase;
use PTS\JwtService\JwtService;

class SetExpireTest extends TestCase
{

    /**
     * @throws \ReflectionException
     */
    public function testSetExpire(): void
    {
        /** @var Claim\Factory $claimFactory */
        $claimFactory = $this->createMock(Claim\Factory::class);
        $service = new JwtService(new Hs512(''), new Jwt, $claimFactory);

        $prop = new \ReflectionProperty(JwtService::class, 'expireSec');
        $prop->setAccessible(true);
        $before = $prop->getValue($service);

        $service->setExpire(3600);
        $after = $prop->getValue($service);

        self::assertSame(0, $before);
        self::assertSame(3600, $after);
    }
}