<?php

declare(strict_types=1);

namespace Apiera\Sdk\Interface;

use Apiera\Sdk\DTO\QueryParameters;
use Apiera\Sdk\Interface\DTO\JsonLDInterface;
use Apiera\Sdk\Interface\DTO\RequestInterface;
use Apiera\Sdk\Interface\DTO\ResponseInterface;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @package Apiera\Sdk\Interface
 * @since 0.1.0
 */
interface RequestResourceInterface
{
    /**
     * @param RequestInterface $request
     * @param QueryParameters|null $params
     * @return JsonLDInterface
     */
    public function find(RequestInterface $request, ?QueryParameters $params = null): JsonLDInterface;

    /**
     * @param RequestInterface $request
     * @param QueryParameters|null $params
     * @return ResponseInterface
     */
    public function findOneBy(RequestInterface $request, ?QueryParameters $params = null): ResponseInterface;

    /**
     * @param RequestInterface $request
     * @return ResponseInterface
     */

    public function get(RequestInterface $request): ResponseInterface;

    /**
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function create(RequestInterface $request): ResponseInterface;

    /**
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function update(RequestInterface $request): ResponseInterface;

    /**
     * @param RequestInterface $request
     * @return void
     */
    public function delete(RequestInterface $request): void;
}
