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
readonly class AlternateIdentifierResponse extends AbstractResponse implements ResponseInterface
{
    /**
     * @param string $id
     * @param LdType $ldType
     * @param Uuid $uuid
     * @param DateTimeInterface $createdAt
     * @param DateTimeInterface $updatedAt
     * @param string $type
     * @param string $code
     */
    public function __construct(
        string $id,
        LdType $ldType,
        Uuid $uuid,
        DateTimeInterface $createdAt,
        DateTimeInterface $updatedAt,
        private string $type,
        private string $code
    ) {
        parent::__construct(
            $id,
            $ldType,
            $uuid,
            $createdAt,
            $updatedAt
        );
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }
}
