<?php

declare(strict_types=1);

namespace AchyutN\NCM\Data\Order;

use AchyutN\NCM\Data\BaseData;
use Illuminate\Support\Carbon;

/**
 * @phpstan-type OrderData array{
 *      orderid: int,
 *      weight?: float,
 *      delivery_charge: float,
 *      delivery_type?: string,
 *      cod_charge?: float,
 *      active?: bool,
 *      delivered_date?: string
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
    public ?float $weight;

    /**
     * The delivery charge for the order.
     */
    public float $deliveryCharge;

    /**
     * The delivery type of the order.
     */
    public ?string $deliveryType;

    /**
     * The cash on delivery charge for the order.
     */
    public ?float $codCharge;

    /**
     * Whether the order is active.
     */
    public ?bool $active;

    /**
     * The delivery date of the order.
     */
    public ?Carbon $deliveryDate = null;


    /**
     * Populate the object from the response array.
     */
    protected function fromResponse(array $response): void
    {
        $this->orderid = $response['orderid'];
        $this->weight = isset($response['weight']) ? (float) $response['weight'] : null;
        $this->deliveryCharge = (float) $response['delivery_charge'];
        $this->deliveryType = $response['delivery_type'] ?? null;
        $this->codCharge = isset($response['cod_charge']) ? (float) $response['cod_charge'] : null;
        $this->active = isset($response['active']) ? (bool) $response['active'] : null;
        $this->deliveryDate = isset($response['delivered_date']) ? Carbon::parse($response['delivered_date']) : null;
    }
}
