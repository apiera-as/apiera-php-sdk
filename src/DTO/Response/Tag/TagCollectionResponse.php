<?php

declare(strict_types=1);

namespace Apiera\Sdk\DTO\Response\Tag;

use Apiera\Sdk\DTO\Response\AbstractCollectionResponse;

/**
 * @author Marie Rinden <marie@shoppingnorge.no>
 * @since 1.0.0
 */
final readonly class TagCollectionResponse extends AbstractCollectionResponse
{
    /**
     * @return array<TagResponse>
     */
    public function getLdMembers(): array
    {
        /** @var array<TagResponse> $members */
        $members = parent::getLdMembers();

        return $members;
    }
}
