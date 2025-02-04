<?php

declare(strict_types=1);

namespace Apiera\Sdk\Transformer;

use Apiera\Sdk\Enum\ProductType;
use Apiera\Sdk\Exception\TransformationException;
use Apiera\Sdk\Interface\TransformerInterface;
use Throwable;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @since 0.3.0
 */
final class ProductTypeTransformer implements TransformerInterface
{
    /**
     * @throws TransformationException
     *
     * @param string $data ProductType string
     */
    public function transform(mixed $data): ProductType
    {
        try {
            return ProductType::from($data);
        } catch (Throwable $exception) {
            throw new TransformationException('Invalid ProductType', previous: $exception);
        }
    }

    /**
     * @throws TransformationException
     */
    public function reverseTransform(mixed $data): string
    {
        if (!$data instanceof ProductType) {
            throw new TransformationException('Expected ProductType');
        }

        return $data->value;
    }
}
