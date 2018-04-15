<?php
declare(strict_types=1);

use Emarref\Jwt\Algorithm\Hs512;
use Emarref\Jwt\Claim;
use Emarref\Jwt\Jwt;
use PHPUnit\Framework\TestCase;
use PTS\JwtService\JwtService;

class AudienceTest extends TestCase
{
    /** @var JwtService */
    protected $service;

    /**
     * @inheritdoc
     */
    public function setUp()
    {
        parent::setUp();

        $this->service = new JwtService(new Hs512(''));
    }

    /**
     * @param string $value
     * @param string $expected
     *
     * @dataProvider dataProvider
     * @throws \ReflectionException
     */
    public function testSetAudience(string $value, string $expected): void
    {
        $this->service->setAudience($value);

        $prop = new \ReflectionProperty(JwtService::class, 'audience');
        $prop->setAccessible(true);
        $actual = $prop->getValue($this->service);

        self::assertSame($expected, $actual);
    }

    /**
     * @param string $value
     * @param string $expected
     *
     * @dataProvider dataProvider
     */
    public function testGetAudience(string $value, string $expected): void
    {
        $this->service->setAudience($value);
        $actual = $this->service->getAudience();

        self::assertSame($expected, $actual);
    }

    public function dataProvider(): array
    {
        return [
            ['site', 'site'],
            ['service_1', 'service_1'],
            ['service_2', 'service_2'],
        ];
    }
}