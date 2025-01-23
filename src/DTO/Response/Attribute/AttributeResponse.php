<?php

namespace Apiera\Sdk\DTO\Response\Attribute;

use Apiera\Sdk\DTO\Response\AbstractResponse;
use Apiera\Sdk\Enum\LdType;
use Apiera\Sdk\Interface\DTO\ResponseInterface;
use DateTimeInterface;
use Symfony\Component\Uid\Uuid;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @package Apiera\Sdk\DTO\Response\Attribute
 * @since 0.2.0
 */
final readonly class AttributeResponse extends AbstractResponse implements ResponseInterface
{
    /**
     * @param string $id
     * @param LdType $type
     * @param Uuid $uuid
     * @param DateTimeInterface $createdAt
     * @param DateTimeInterface $updatedAt
     * @param string $name The attribute name
     * @param string $store The attribute store iri
     */
    public function __construct(
        string $id,
        LdType $type,
        Uuid $uuid,
        DateTimeInterface $createdAt,
        DateTimeInterface $updatedAt,
        private string $name,
        private string $store,
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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getStore(): string
    {
        return $this->store;
    }
}
