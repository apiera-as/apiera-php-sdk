<?php

namespace Apiera\Sdk\DTO\Response\AlternateIdentifier;

use Apiera\Sdk\DTO\Response\AbstractResponse;
use Apiera\Sdk\Enum\LdType;
use Apiera\Sdk\Interface\DTO\ResponseInterface;
use DateTimeInterface;
use Symfony\Component\Uid\Uuid;

/**
 * @author Marie Rinden <marie@shoppingnorge.no>
 * @package Apiera\Sdk\DTO\Response\Category
 * @since 0.2.0
 */
final readonly class AlternateIdentifierResponse extends AbstractResponse implements ResponseInterface
{
    /**
     * @param string $id
     * @param LdType $type
     * @param Uuid $uuid
     * @param DateTimeInterface $createdAt
     * @param DateTimeInterface $updatedAt
     * Using "identifierType" instead of "type" to avoid conflicts with the parent class.
     * @param string $identifierType
     * @param string $code
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

    /**
     * @return string
     */
    public function getIdentifierType(): string
    {
        return $this->identifierType;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }
}
