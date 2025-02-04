<?php

declare(strict_types=1);

namespace Apiera\Sdk\Interface;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @since 0.3.0
 */
interface TransformerInterface
{
    /**
     * @throws \Apiera\Sdk\Exception\TransformationException
     */
    public function transform(mixed $data): mixed;

    /**
     * @throws \Apiera\Sdk\Exception\TransformationException
     */
    public function reverseTransform(mixed $data): mixed;
}
