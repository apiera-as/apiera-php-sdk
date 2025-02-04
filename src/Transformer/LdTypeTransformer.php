<?php

declare(strict_types=1);

namespace Apiera\Sdk\Transformer;

use Apiera\Sdk\Enum\LdType;
use Apiera\Sdk\Exception\TransformationException;
use Apiera\Sdk\Interface\TransformerInterface;
use Throwable;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @since 0.3.0
 */
final class LdTypeTransformer implements TransformerInterface
{
    /**
     * @throws TransformationException
     *
     * @param string $data LdType string
     */
    public function transform(mixed $data): LdType
    {
        try {
            return LdType::from($data);
        } catch (Throwable $exception) {
            throw new TransformationException('Invalid LdType', previous: $exception);
        }
    }

    /**
     * @throws TransformationException
     */
    public function reverseTransform(mixed $data): string
    {
        if (!$data instanceof LdType) {
            throw new TransformationException('Expected LdType');
        }

        return $data->value;
    }
}
