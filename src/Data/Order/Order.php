<?php

declare(strict_types=1);

namespace AchyutN\NCM\Data\Order;

use AchyutN\NCM\Data\BaseData;
use AchyutN\NCM\Exceptions\NCMException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

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

    /**
     * @return Collection<int, OrderStatus>
     *
     * @throws NCMException
     */
    public function getStatus(): Collection
    {
        return $this->ncm->getOrderStatus($this->orderid);
    }

    protected function fromResponse(array $response): void
    {
        $this->orderid = $response['orderid'];
        $this->deliveryCharge = (float) ($response['delivery_charge'] ?? 0);
        $this->deliveryType = $response['delivery_type'] ?? null;

        if (isset($response['weight'])) {
            $weight = $response['weight'];
            $this->weight = is_numeric($weight) ? (float) $weight : null;
        }

        if (isset($response['cod_charge'])) {
            $cod = $response['cod_charge'];
            $this->codCharge = is_numeric($cod) ? (float) $cod : null;
        }

        if (isset($response['active'])) {
            $this->active = $response['active'];
        }
        $this->deliveryDate = isset($response['delivered_date']) ? Carbon::parse($response['delivered_date']) : null;
    }
}
