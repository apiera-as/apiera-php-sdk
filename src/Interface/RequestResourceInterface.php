<?php

declare(strict_types=1);

namespace Apiera\Sdk\Interface;

use Apiera\Sdk\DTO\QueryParameters;
use Apiera\Sdk\Interface\DTO\JsonLDInterface;
use Apiera\Sdk\Interface\DTO\RequestInterface;
use Apiera\Sdk\Interface\DTO\ResponseInterface;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @since 0.1.0
 */
interface RequestResourceInterface
{
    public function find(RequestInterface $request, ?QueryParameters $params = null): JsonLDInterface;

    public function findOneBy(RequestInterface $request, QueryParameters $params): ResponseInterface;

    public function get(RequestInterface $request): ResponseInterface;

    public function create(RequestInterface $request): ResponseInterface;

    public function update(RequestInterface $request): ResponseInterface;

    public function delete(RequestInterface $request): void;
}
