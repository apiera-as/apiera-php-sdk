<?php

declare(strict_types=1);

namespace Apiera\Sdk\DTO\Response\Distributor;

use Apiera\Sdk\DTO\Response\AbstractCollectionResponse;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @since 0.3.0
 */
final readonly class DistributorCollectionResponse extends AbstractCollectionResponse
{
    /**
     * @return array<DistributorResponse>
     */
    public function getMembers(): array
    {
        /** @var array<DistributorResponse> $members */
        $members = parent::getMembers();

        return $members;
    }
}
