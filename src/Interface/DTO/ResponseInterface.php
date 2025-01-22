<?php

declare(strict_types=1);

namespace Apiera\Sdk\Interface\DTO;

use DateTimeInterface;
use Symfony\Component\Uid\Uuid;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @package Apiera\Sdk\Interface\DTO
 * @since 0.1.0
 */
interface ResponseInterface extends JsonLDInterface
{
    /**
     * @return Uuid
     */
    public function getUuid(): Uuid;

    /**
     * @return DateTimeInterface
     */
    public function getCreatedAt(): DateTimeInterface;

    /**
     * @return DateTimeInterface
     */
    public function getUpdatedAt(): DateTimeInterface;
}
