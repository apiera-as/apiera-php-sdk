<?php

declare(strict_types=1);

namespace Apiera\Sdk\DataMapper;

use Apiera\Sdk\Attribute\SkipRequest;
use Apiera\Sdk\Enum\LdType;
use Apiera\Sdk\Enum\ResponseType;
use Apiera\Sdk\Exception\ClientException;
use Apiera\Sdk\Interface\DataMapperInterface;
use Apiera\Sdk\Interface\DTO\JsonLDInterface;
use Apiera\Sdk\Interface\DTO\RequestInterface;
use Apiera\Sdk\Interface\DTO\ResponseInterface;
use Apiera\Sdk\Interface\RequestFieldInterface;
use Apiera\Sdk\Interface\ResponseFieldInterface;
use Apiera\Sdk\Interface\TransformerInterface;
use InvalidArgumentException;
use ReflectionAttribute;
use ReflectionClass;
use Throwable;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @since 0.3.0
 */
final class ReflectionAttributeDataMapper implements DataMapperInterface
{
    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     *
     * @param array<string, mixed> $responseData
     */
    public function fromResponse(array $responseData): ResponseInterface
    {
        try {
            $type = LdType::from($responseData['@type']);
            /** @var class-string<ResponseInterface> $responseClass */
            $responseClass = LdType::getResponseClassForType($type, ResponseType::Single);
            $reflectionClass = new ReflectionClass($responseClass);

            return $reflectionClass->newInstanceArgs($this->mapResponseData($responseData, $reflectionClass));
        } catch (Throwable $exception) {
            throw new ClientException(
                message: 'Failed to map response data: ' . $exception->getMessage(),
                previous: $exception
            );
        }
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     *
     * @param array<string, mixed> $collectionResponseData
     */
    public function fromCollectionResponse(array $collectionResponseData): JsonLDInterface
    {
        try {
            if ($collectionResponseData['@type'] !== LdType::Collection->value) {
                throw new ClientException('Invalid collection type');
            }

            $context = $collectionResponseData['@context'];
            $contextParts = explode('/', rtrim($context));
            $contextType = end($contextParts);

            /** @var class-string<\Apiera\Sdk\Interface\DTO\JsonLDCollectionInterface> $collectionClass */
            $collectionClass = LdType::getResponseClassForType(
                LdType::from($contextType),
                ResponseType::Collection
            );

            return new $collectionClass(
                context: $collectionResponseData['@context'],
                id: $collectionResponseData['@id'],
                type: LdType::from($collectionResponseData['@type']),
                members: array_map(
                    fn(array $member): ResponseInterface => $this->fromResponse($member),
                    $collectionResponseData['member']
                ),
                totalItems: $collectionResponseData['totalItems'],
                view: $collectionResponseData['view'] ?? null,
                firstPage: $collectionResponseData['firstPage'] ?? null,
                lastPage: $collectionResponseData['lastPage'] ?? null,
                nextPage: $collectionResponseData['nextPage'] ?? null,
                previousPage: $collectionResponseData['previousPage'] ?? null,
            );
        } catch (Throwable $exception) {
            throw new ClientException(
                message: 'Failed to map collection data: ' . $exception->getMessage(),
                previous: $exception
            );
        }
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     *
     * @return array<string, mixed>
     */
    public function toRequestData(RequestInterface $requestDto): array
    {
        try {
            $reflectionClass = new ReflectionClass($requestDto);
            $requestData = [];

            foreach ($reflectionClass->getProperties() as $property) {
                if (count($property->getAttributes(SkipRequest::class)) !== 0) {
                    continue;
                }

                $attributes = array_filter(
                    $property->getAttributes(),
                    fn(ReflectionAttribute $attr) => is_subclass_of(
                        $attr->getName(),
                        RequestFieldInterface::class
                    )
                );

                if (count($attributes) === 0) {
                    continue;
                }

                /** @var RequestFieldInterface $requestField */
                $requestField = $attributes[0]->newInstance();
                $apiFieldName = $requestField->getName();
                $apiFieldValue = $property->getValue($requestDto);

                if ($apiFieldValue !== null && $requestField->getTransformerClass() !== null) {
                    $transformerClass = $requestField->getTransformerClass();
                    $transformer = new $transformerClass();

                    if (!$transformer instanceof TransformerInterface) {
                        throw new InvalidArgumentException('Transformer must implement TransformerInterface');
                    }

                    $apiFieldValue = $transformer->reverseTransform($apiFieldValue);
                }

                $requestData[$apiFieldName] = $apiFieldValue;
            }

            return $requestData;
        } catch (Throwable $exception) {
            throw new ClientException(
                message: 'Failed to map request data: ' . $exception->getMessage(),
                previous: $exception
            );
        }
    }

    /**
     * @throws \Apiera\Sdk\Exception\TransformationException
     *
     * @param array<string, mixed> $responseData
     * @param ReflectionClass<ResponseInterface> $reflectionClass
     *
     * @return array<string, mixed>
     */
    private function mapResponseData(array $responseData, ReflectionClass $reflectionClass): array
    {
        $constructorArguments = [];

        foreach ($reflectionClass->getProperties() as $property) {
            $attributes = array_filter(
                $property->getAttributes(),
                fn(ReflectionAttribute $attr) => is_subclass_of(
                    $attr->getName(),
                    ResponseFieldInterface::class
                )
            );

            if (count($attributes) === 0) {
                continue;
            }

            /** @var ResponseFieldInterface $responseField */
            $responseField = $attributes[0]->newInstance();
            $apiFieldName = $responseField->getName();
            $apiFieldValue = $responseData[$apiFieldName];

            if ($apiFieldValue !== null && $responseField->getTransformerClass() !== null) {
                $transformerClass = $responseField->getTransformerClass();
                $transformer = new $transformerClass();

                if (!$transformer instanceof TransformerInterface) {
                    throw new InvalidArgumentException('Transformer must implement TransformerInterface');
                }

                $apiFieldValue = $transformer->transform($apiFieldValue);
            }

            $constructorArguments[$property->getName()] = $apiFieldValue;
        }

        return $constructorArguments;
    }
}
