<?php

declare(strict_types=1);

namespace Apiera\Sdk\Interface\DTO;

use DateTimeInterface;
use Symfony\Component\Uid\Uuid;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @since 0.1.0
 */
interface ResponseInterface extends JsonLDInterface
{
    public function getUuid(): Uuid;

    public function getCreatedAt(): DateTimeInterface;

    public function getUpdatedAt(): DateTimeInterface;
}
