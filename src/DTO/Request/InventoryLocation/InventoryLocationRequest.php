<?php

declare(strict_types=1);

namespace Apiera\Sdk\DTO\Request\InventoryLocation;

use Apiera\Sdk\Interface\DTO\RequestInterface;

/**
 * @author Marie Rinden <marie@shoppingnorge.no>
 * @since 0.3.0
 */
final readonly class InventoryLocationRequest implements RequestInterface
{
    public function __construct(
        private string $name,
        private ?string $store = null,
        private ?string $address1 = null,
        private ?string $address2 = null,
        private ?string $city = null,
        private ?string $state = null,
        private ?string $zipCode = null,
        private ?string $country = null,
        private ?string $phone = null,
        private ?string $email = null,
        private ?string $iri = null,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getStore(): ?string
    {
        return $this->store;
    }

    public function getAddress1(): ?string
    {
        return $this->address1;
    }

    public function getAddress2(): ?string
    {
        return $this->address2;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getIri(): ?string
    {
        return $this->iri;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'store' => $this->store,
            'address1' => $this->address1,
            'address2' => $this->address2,
            'city' => $this->city,
            'state' => $this->state,
            'zipCode' => $this->zipCode,
            'country' => $this->country,
            'phone' => $this->phone,
            'email' => $this->email,
        ];
    }
}
