<?php

declare(strict_types=1);

namespace Apiera\Sdk\DTO\Response\Brand;

use Apiera\Sdk\DTO\Response\AbstractCollectionResponse;
use Apiera\Sdk\Interface\DTO\ResponseInterface;

/**
 * * @author Marie Rinden <marie@shoppingnorge.no>
 * @since 0.3.0
*/
final readonly class BrandCollectionResponse extends AbstractCollectionResponse
{
    /**
     * @return array<ResponseInterface>
     */
    public function getMembers(): array
    {
        /** @var array<BrandResponse> $members */
        $members = parent::getMembers();

        return $members;
    }
}