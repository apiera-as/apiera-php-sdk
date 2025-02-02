<?php

declare(strict_types=1);

namespace Apiera\Sdk\DataMapper;

use Apiera\Sdk\Attribute\SkipRequest;
use Apiera\Sdk\Enum\LdType;
use Apiera\Sdk\Enum\ResponseType;
use Apiera\Sdk\Exception\Mapping\RequestMappingException;
use Apiera\Sdk\Exception\Mapping\ResponseMappingException;
use Apiera\Sdk\Interface\DataMapperInterface;
use Apiera\Sdk\Interface\DTO\JsonLDCollectionInterface;
use Apiera\Sdk\Interface\DTO\RequestInterface;
use Apiera\Sdk\Interface\DTO\ResponseInterface;
use Apiera\Sdk\Interface\RequestFieldInterface;
use Apiera\Sdk\Interface\ResponseFieldInterface;
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
     * @throws ResponseMappingException
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
            throw new ResponseMappingException(
                'Failed to map response data to DTO',
                $responseData,
                $responseClass ?? 'unknown',
                $exception
            );
        }
    }

    /**
     * @throws ResponseMappingException
     *
     * @param array<string, mixed> $collectionResponseData
     */
    public function fromCollectionResponse(array $collectionResponseData): JsonLDCollectionInterface
    {
        try {
            if ($collectionResponseData['@type'] !== LdType::Collection->value) {
                throw new ResponseMappingException(
                    'Invalid collection type',
                    $collectionResponseData,
                    'collection'
                );
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
            throw new ResponseMappingException(
                'Failed to map collection data',
                $collectionResponseData,
                'collection',
                $exception
            );
        }
    }

    /**
     * @throws RequestMappingException
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
                    /** @var \Apiera\Sdk\Interface\TransformerInterface $transformer */
                    $transformer = new $transformerClass();

                    $apiFieldValue = $transformer->reverseTransform($apiFieldValue);
                }

                $requestData[$apiFieldName] = $apiFieldValue;
            }

            return $requestData;
        } catch (Throwable $exception) {
            throw new RequestMappingException(
                'Failed to map request DTO to API data',
                $requestDto,
                $exception
            );
        }
    }

    /**
     * @throws ResponseMappingException
     *
     * @param array<string, mixed> $responseData
     * @param ReflectionClass<ResponseInterface> $reflectionClass
     *
     * @return array<string, mixed>
     */
    private function mapResponseData(array $responseData, ReflectionClass $reflectionClass): array
    {
        try {
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
                    /** @var \Apiera\Sdk\Interface\TransformerInterface $transformer */
                    $transformer = new $transformerClass();

                    $apiFieldValue = $transformer->transform($apiFieldValue);
                }

                $constructorArguments[$property->getName()] = $apiFieldValue;
            }

            return $constructorArguments;
        } catch (Throwable $exception) {
            throw new ResponseMappingException(
                'Failed to map response fields',
                $responseData,
                $reflectionClass->getName(),
                $exception
            );
        }
    }
}
