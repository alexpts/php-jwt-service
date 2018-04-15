<?php
declare(strict_types=1);

namespace PTS\JwtService;

use Emarref\Jwt\Algorithm\AlgorithmInterface;
use Emarref\Jwt\Encryption\EncryptionInterface;
use Emarref\Jwt\Jwt;
use Emarref\Jwt\Token;
use Emarref\Jwt\Claim;
use Emarref\Jwt\Claim\Factory as ClaimFactory;
use Emarref\Jwt\Encryption\Factory as EncryptionFactory;
use Emarref\Jwt\Verification\Context as VerificationContext;

class JwtService
{
    /** @var Jwt */
    protected $lib;
    /** @var ClaimFactory */
    protected $claimFactory;
    /** @var EncryptionInterface */
    protected $encryption;
    /** @var string|null */
    protected $audience;
    /** @var int */
    protected $expireSec = 0;
    /** @var Token */
    protected $emptyToken;

    public function __construct(AlgorithmInterface $algorithm)
    {
        $this->claimFactory = new ClaimFactory;
        $this->lib = new Jwt;
        $this->encryption = EncryptionFactory::create($algorithm);
        $this->emptyToken = new Token;
    }

    public function setAudience(string $audience): self
    {
        $this->audience = $audience;
        return $this;
    }

    public function getAudience(): ?string
    {
        return $this->audience;
    }

    public function getEncryption(): EncryptionInterface
    {
        return $this->encryption;
    }

    public function setExpire(int $sec): self
    {
        $this->expireSec = $sec;
        return $this;
    }

    public function encode(array $payload = []): string
    {
        $token = $this->createToken($payload);
        return $this->getLib()->serialize($token, $this->getEncryption());
    }

    public function createToken(array $payload): Token
    {
        $token = $this->getEmptyToken();
        $this->setPayloadClaim($payload, $token);

        $now = time();
        $token->addClaim(new Claim\IssuedAt($now));
        $token->addClaim(new Claim\NotBefore($now));

        if ($this->getAudience()) {
            $token->addClaim(new Claim\Audience($this->getAudience()));
        }
        if ($this->expireSec > 0) {
            $token->addClaim(new Claim\Expiration($now + $this->expireSec));
        }

        return $token;
    }

    public function decode(string $serializedToken): Token
    {
        return $this->getLib()->deserialize($serializedToken);
    }

    /**
     * @param Token $token
     * @param VerificationContext $verificationContext
     *
     * @throws VerifyTokenException
     */
    public function verify(Token $token, VerificationContext $verificationContext): void
    {
        if ($this->getAudience()) {
            $verificationContext->setAudience($this->getAudience());
        }
        $verificationContext->setEncryption($this->getEncryption());

        try {
            $this->getLib()->verify($token, $verificationContext);
        } catch (\Exception $exception) {
            throw new VerifyTokenException('Token did not pass the verification', $exception->getCode(), $exception);
        }
    }

    public function getData(Token $token): array
    {
        $data = [];
        foreach ($token->getPayload()->getClaims()->getIterator() as $property) {
            $name = $property->getName();
            $data[$name] = $property->getValue();
        }

        return $data;
    }

    protected function getEmptyToken(): Token
    {
        return clone $this->emptyToken;
    }

    protected function setPayloadClaim(array $payload, Token $token): void
    {
        $claimFactory = $this->getClaimFactory();

        foreach ($payload as $name => $value) {
            $claim = $claimFactory->get($name);
            $claim->setValue($value);
            $token->addClaim($claim);
        }
    }

    protected function getClaimFactory(): ClaimFactory
    {
        return $this->claimFactory;
    }

    protected function getLib(): Jwt
    {
        return $this->lib;
    }
}
