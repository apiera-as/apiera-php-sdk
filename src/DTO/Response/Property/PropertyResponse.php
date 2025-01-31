<?php

declare(strict_types=1);

namespace Apiera\Sdk\DTO\Response\Property;

use Apiera\Sdk\DTO\Response\AbstractResponse;
use Apiera\Sdk\Enum\LdType;
use DateTimeInterface;
use Symfony\Component\Uid\Uuid;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @since 0.3.0
 */
final readonly class PropertyResponse extends AbstractResponse
{
    /**
     * @param string $name The property name
     * @param string $store The store iri
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

    public function getName(): string
    {
        return $this->name;
    }

    public function getStore(): string
    {
        return $this->store;
    }
}
