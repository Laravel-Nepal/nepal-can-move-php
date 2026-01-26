<?php

declare(strict_types=1);

namespace AchyutN\NCM\Data;

use AchyutN\NCM\Enums\DeliveryType;

final class CreateOrderRequest
{
    public function __construct(
        public string $name,
        public string $phone,
        public string $codCharge,
        public string $address,
        public string $fbranch,
        public string $branch,
        public ?string $phone2 = '',
        public ?string $package = '',
        public ?string $vrefId = '',
        public ?string $instruction = '',
        public DeliveryType $deliveryType = DeliveryType::DoorToDoor,
        public string $weight = '1'
    ) {}

    /**
     * Convert the DTO to the array format expected by NCM API.
     */
    public function toArray(): array // @phpstan-ignore-line
    {
        return [
            'name' => $this->name,
            'phone' => $this->phone,
            'phone2' => $this->phone2,
            'cod_charge' => $this->codCharge,
            'address' => $this->address,
            'fbranch' => $this->fbranch,
            'branch' => $this->branch,
            'package' => $this->package,
            'vref_id' => $this->vrefId,
            'instruction' => $this->instruction,
            'delivery_type' => $this->deliveryType->toOrderCreateValue(),
            'weight' => $this->weight,
        ];
    }
}
