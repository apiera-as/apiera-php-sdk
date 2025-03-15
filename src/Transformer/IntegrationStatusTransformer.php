<?php

declare(strict_types=1);

namespace Apiera\Sdk\Transformer;

use Apiera\Sdk\Enum\IntegrationStatus;
use Apiera\Sdk\Exception\TransformationException;
use Apiera\Sdk\Interface\TransformerInterface;
use Throwable;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @since 2.1.0
 */
final class IntegrationStatusTransformer implements TransformerInterface
{
    /**
     * @throws TransformationException
     *
     * @param class-string<IntegrationStatus> $data
     */
    public function transform(mixed $data): IntegrationStatus
    {
        try {
            return IntegrationStatus::from($data);
        } catch (Throwable $exception) {
            throw new TransformationException('Invalid IntegrationStatus', previous: $exception);
        }
    }

    /**
     * @throws TransformationException
     */
    public function reverseTransform(mixed $data): string
    {
        if (!$data instanceof IntegrationStatus) {
            throw new TransformationException('Expected IntegrationStatus');
        }

        return $data->value;
    }
}
