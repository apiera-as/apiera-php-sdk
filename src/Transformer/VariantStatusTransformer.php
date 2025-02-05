<?php

declare(strict_types=1);

namespace Apiera\Sdk\Transformer;

use Apiera\Sdk\Enum\VariantStatus;
use Apiera\Sdk\Exception\TransformationException;
use Apiera\Sdk\Interface\TransformerInterface;
use Throwable;

final class VariantStatusTransformer implements TransformerInterface
{
    /**
     * @throws TransformationException
     *
     * @param string $data VariantStatus string
     */
    public function transform(mixed $data): VariantStatus
    {
        try {
            return VariantStatus::from($data);
        } catch (Throwable $exception) {
            throw new TransformationException('Invalid VariantStatus', previous: $exception);
        }
    }

    /**
     * @throws TransformationException
     */
    public function reverseTransform(mixed $data): string
    {
        if (!$data instanceof VariantStatus) {
            throw new TransformationException('Expected VariantStatus');
        }

        return $data->value;
    }
}
