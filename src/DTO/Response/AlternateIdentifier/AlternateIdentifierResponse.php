<?php

declare(strict_types=1);

namespace Apiera\Sdk\DTO\Response\AlternateIdentifier;

use Apiera\Sdk\DTO\Response\AbstractResponse;
use Apiera\Sdk\Enum\LdType;
use Apiera\Sdk\Interface\DTO\ResponseInterface;
use DateTimeInterface;
use Symfony\Component\Uid\Uuid;

/**
 * @author Marie Rinden <marie@shoppingnorge.no>
 * @since 0.2.0
 */
final readonly class AlternateIdentifierResponse extends AbstractResponse implements ResponseInterface
{
    /**
     * @param string $identifierType Using "identifierType" instead of "type" to avoid conflicts with the parent class.
     */
    public function __construct(
        string $id,
        LdType $type,
        Uuid $uuid,
        DateTimeInterface $createdAt,
        DateTimeInterface $updatedAt,
        private string $identifierType,
        private string $code
    ) {
        parent::__construct(
            $id,
            $type,
            $uuid,
            $createdAt,
            $updatedAt
        );
    }

    public function getIdentifierType(): string
    {
        return $this->identifierType;
    }

    public function getCode(): string
    {
        return $this->code;
    }
}
