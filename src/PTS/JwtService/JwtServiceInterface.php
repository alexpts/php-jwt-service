<?php
declare(strict_types=1);

namespace PTS\JwtService;

use Emarref\Jwt\Encryption\EncryptionInterface;
use Emarref\Jwt\Token;
use Emarref\Jwt\Verification\Context as VerificationContext;

interface JwtServiceInterface
{

    public function setAudience(string $audience): self;

    public function getAudience(): ?string;

    public function getEncryption(): EncryptionInterface;

    public function setExpire(int $sec): self;

    public function encode(array $payload = []): string;

    public function decode(string $serializedToken): Token;

    public function verify(Token $token, VerificationContext $verificationContext): void;

    public function getData(Token $token): array;
}
