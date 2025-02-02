<?php

declare(strict_types=1);

namespace Apiera\Sdk\Transformer;

use Apiera\Sdk\Exception\TransformationException;
use Apiera\Sdk\Interface\TransformerInterface;
use DateTimeImmutable;
use DateTimeInterface;
use Throwable;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @since 0.3.0
 */
final class DateTimeTransformer implements TransformerInterface
{
    /**
     * @throws TransformationException
     *
     * @param string $data datetime string
     */
    public function transform(mixed $data): DateTimeInterface
    {
        try {
            return new DateTimeImmutable($data);
        } catch (Throwable $exception) {
            throw new TransformationException('Invalid date', previous: $exception);
        }
    }

    /**
     * @throws TransformationException
     */
    public function reverseTransform(mixed $data): string
    {
        if (!$data instanceof DateTimeInterface) {
            throw new TransformationException('Expected DateTimeInterface');
        }

        return $data->format(DateTimeInterface::ATOM);
    }
}
