<?php

declare(strict_types=1);

namespace Apiera\Sdk\Exception\Http;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Throwable;

/**
 * 422 Validation errors
 *
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @since 0.3.0
 */
final class UnprocessableEntityException extends ApiException
{
    /**
     * @param array<string, array<string>> $violations
     */
    public function __construct(
        private readonly array $violations = [],
        ?RequestInterface $request = null,
        ?ResponseInterface $response = null,
        ?Throwable $previous = null
    ) {
        $message = count($violations) > 0
            ? 'Validation failed: ' . $this->formatViolations($violations)
            : 'Validation failed';
        parent::__construct(
            $message,
            $request,
            $response,
            $previous,
            ['violations' => $violations]
        );
    }

    /**
     * @return array<string, array<string>>
     */
    public function getViolations(): array
    {
        return $this->violations;
    }

    /**
     * @param array<string, array<string>> $violations
     */
    private function formatViolations(array $violations): string
    {
        $messages = [];

        foreach ($violations as $field => $errors) {
            $messages[] = sprintf('%s: %s', $field, implode(', ', $errors));
        }

        return implode('; ', $messages);
    }
}
