<?php

declare(strict_types=1);

namespace Apiera\Sdk\Transformer;

use Apiera\Sdk\Enum\ProductStatus;
use Apiera\Sdk\Exception\TransformationException;
use Apiera\Sdk\Interface\TransformerInterface;
use Throwable;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @since 0.3.0
 */
final class ProductStatusTransformer implements TransformerInterface
{
    /**
     * @throws TransformationException
     *
     * @param string $data ProductStatus string
     */
    public function transform(mixed $data): ProductStatus
    {
        try {
            return ProductStatus::from($data);
        } catch (Throwable $exception) {
            throw new TransformationException('Invalid ProductStatus', previous: $exception);
        }
    }

    /**
     * @throws TransformationException
     */
    public function reverseTransform(mixed $data): string
    {
        if (!$data instanceof ProductStatus) {
            throw new TransformationException('Expected ProductStatus');
        }

        return $data->value;
    }
}
