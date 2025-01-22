<?php

declare(strict_types=1);

namespace Apiera\Sdk\DTO\Response\Category;

use Apiera\Sdk\DTO\Response\AbstractResponse;
use Apiera\Sdk\Enum\LdType;
use Apiera\Sdk\Interface\DTO\ResponseInterface;
use DateTimeInterface;
use Symfony\Component\Uid\Uuid;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @package Apiera\Sdk\DTO\Response\Category
 * @since 1.0.0
 */
final readonly class CategoryResponse extends AbstractResponse implements ResponseInterface
{
    /**
     * @param string $id
     * @param LdType $type
     * @param Uuid $uuid
     * @param DateTimeInterface $createdAt
     * @param DateTimeInterface $updatedAt
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

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return string|null
     */
    public function getParent(): ?string
    {
        return $this->parent;
    }

    /**
     * @return string
     */
    public function getStore(): string
    {
        return $this->store;
    }

    /**
     * @return string|null
     */
    public function getImage(): ?string
    {
        return $this->image;
    }
}
