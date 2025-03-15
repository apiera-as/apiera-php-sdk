<?php

declare(strict_types=1);

namespace Apiera\Sdk\Transformer;

use Apiera\Sdk\Enum\IntegrationProtocol;
use Apiera\Sdk\Exception\TransformationException;
use Apiera\Sdk\Interface\TransformerInterface;
use Throwable;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @since 2.1.0
 */
final class IntegrationProtocolTransformer implements TransformerInterface
{
    /**
     * @throws TransformationException
     *
     * @param class-string<IntegrationProtocol> $data
     */
    public function transform(mixed $data): IntegrationProtocol
    {
        try {
            return IntegrationProtocol::from($data);
        } catch (Throwable $exception) {
            throw new TransformationException('Invalid IntegrationProtocol', previous: $exception);
        }
    }

    /**
     * @throws TransformationException
     */
    public function reverseTransform(mixed $data): string
    {
        if (!$data instanceof IntegrationProtocol) {
            throw new TransformationException('Expected IntegrationProtocol');
        }

        return $data->value;
    }
}
