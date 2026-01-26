<?php

declare(strict_types=1);

namespace AchyutN\NCM\Data\Order;

final class RedirectOrderRequest
{
    public function __construct(
        public int $orderid,
        public string $name,
        public string $phone,
        public string $address,
        public ?string $vrefId,
        public string $branch,
        public string $codCharge,
    ) {}

    /**
     * Convert the DTO to the array format expected by NCM API.
     */
    public function toArray(): array // @phpstan-ignore-line
    {
        return [
            'pk' => $this->orderid,
            'name' => $this->name,
            'phone' => $this->phone,
            'address' => $this->address,
            'vendorOrderid' => $this->vrefId,
            'destination' => $this->branch,
            'cod_charge' => $this->codCharge,
        ];
    }
}
