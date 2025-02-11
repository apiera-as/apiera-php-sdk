<?php

declare(strict_types=1);

namespace Apiera\Sdk\Transformer;

use Apiera\Sdk\Exception\TransformationException;
use Apiera\Sdk\Interface\TransformerInterface;
use Symfony\Component\Uid\Uuid;
use Throwable;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @since 1.0.0
 */
final class UuidTransformer implements TransformerInterface
{
    /**
     * @throws TransformationException
     *
     * @param string $data uuid string
     */
    public function transform(mixed $data): Uuid
    {
        try {
            return Uuid::fromString($data);
        } catch (Throwable $exception) {
            throw new TransformationException('Invalid UUID', previous: $exception);
        }
    }

    /**
     * @throws TransformationException
     */
    public function reverseTransform(mixed $data): string
    {
        if (!$data instanceof Uuid) {
            throw new TransformationException('Expected Uuid');
        }

        return $data->toString();
    }
}
