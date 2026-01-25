<?php

declare(strict_types=1);

namespace AchyutN\NCM\Data\Order;

use AchyutN\NCM\Data\BaseData;
use Illuminate\Support\Carbon;

/**
 * @phpstan-type OrderData array{
 *      orderid: int,
 *      weight: float|null,
 *      delivery_charge: float,
 *      delivery_type: string|null,
 *      cod_charge: float|null,
 *      active: bool,
 *      delivered_date: string|null
 * }
 *
 * @template-extends BaseData<OrderData>
 */
final class Order extends BaseData
{
    /**
     * The unique ID of the order.
     */
    public int $orderid;

    /**
     * The weight of the order.
     */
    public ?float $weight = null;

    /**
     * The delivery charge for the order.
     */
    public float $deliveryCharge;

    /**
     * The delivery type of the order.
     */
    public ?string $deliveryType = null;

    /**
     * The cash on delivery charge for the order.
     */
    public ?float $codCharge = null;

    /**
     * Whether the order is active.
     */
    public ?bool $active = null;

    /**
     * The delivery date of the order.
     */
    public ?Carbon $deliveryDate = null;

    protected function fromResponse(array $response): void
    {
        $this->orderid = $response['orderid'];
        $this->deliveryCharge = (float) $response['delivery_charge'];
        $this->deliveryType = $response['delivery_type'] ?? null;

        if (! is_null($response['weight'])) {
            $this->weight = (float) $response['weight'];
        }

        if (! is_null($response['cod_charge'])) {
            $this->codCharge = (float) $response['cod_charge'];
        }

        if (array_key_exists('active', $response)) {
            $this->active = $response['active'];
        }

        if (! is_null($response['delivered_date'])) {
            $this->deliveryDate = Carbon::parse($response['delivered_date']);
        }
    }
}
