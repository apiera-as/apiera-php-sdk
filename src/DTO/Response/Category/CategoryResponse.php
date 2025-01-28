<?php

declare(strict_types=1);

namespace Apiera\Sdk\DTO\Response\Category;

use Apiera\Sdk\DTO\Response\AbstractResponse;
use Apiera\Sdk\Enum\LdType;
use DateTimeInterface;
use Symfony\Component\Uid\Uuid;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @since 0.1.0
 */
final readonly class CategoryResponse extends AbstractResponse
{
    /**
     * @param string $name The category name
     * @param string|null $description The category description
     * @param string|null $parent The category parent iri
     * @param string $store The category store iri
     * @param string|null $image The category image iri
     */
    public function __construct(
        string $id,
        LdType $type,
        Uuid $uuid,
        DateTimeInterface $createdAt,
        DateTimeInterface $updatedAt,
        private string $name,
        private string $store,
        private ?string $description = null,
        private ?string $parent = null,
        private ?string $image = null
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getParent(): ?string
    {
        return $this->parent;
    }

    public function getStore(): string
    {
        return $this->store;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }
}
