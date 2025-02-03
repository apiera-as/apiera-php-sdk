<?php

declare(strict_types=1);

namespace Apiera\Sdk\DTO\Response\File;

use Apiera\Sdk\DTO\Response\AbstractCollectionResponse;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @since 0.3.0
 */
final readonly class FileCollectionResponse extends AbstractCollectionResponse
{
    /**
     * @return array<FileResponse>
     */
    public function getLdMembers(): array
    {
        /** @var array<FileResponse> $members */
        $members = parent::getLdMembers();

        return $members;
    }
}
