<?php

declare(strict_types=1);

namespace AchyutN\NCM\Data;

use AchyutN\NCM\Enums\DeliveryType;
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
    public int $id;

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
    public ?DeliveryType $deliveryType = null;

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
    public function statusHistory(): Collection
    {
        return $this->ncm->getOrderStatus($this->id);
    }

    /**
     * @return OrderStatus|null
     *
     * @throws NCMException
     */
    public function status(): ?OrderStatus
    {
        $history = $this->statusHistory();

        if ($history->isEmpty()) {
            return null;
        }

        return $history->sortByDesc(fn (OrderStatus $status) => $status->addedTime)->first();
    }

    /**
     * Get comments of the order.
     *
     * @return Collection<int, Comment>
     *
     * @throws NCMException
     */
    public function comments(): Collection
    {
        return $this->ncm->getOrderComments($this->id);
    }

    /**
     * Add a comment to the order.
     */
    public function addComment(string $comment): bool
    {
        return $this->ncm->addOrderComment($this->id, $comment);
    }

    /**
     * Mark the order for return.
     *
     * @param  string|null  $reason  The reason for return (optional).
     *
     * @throws NCMException
     */
    public function return(?string $reason = null): bool
    {
        return $this->ncm->returnOrder($this->id, $reason);
    }

    /**
     * Mark the order for exchange.
     *
     * @throws NCMException
     */
    public function exchange(): true
    {
        return $this->ncm->exchangeOrder($this->id);
    }

    /**
     * Mark an order for redirect to another customer.
     *
     * @throws NCMException
     */
    public function redirect(RedirectOrderRequest $redirectOrderRequest): bool
    {
        return $this->ncm->redirectOrder($redirectOrderRequest);
    }

    protected function fromResponse(array $response): void
    {
        $this->id = $response['orderid'];
        $this->deliveryCharge = (float) ($response['delivery_charge'] ?? 0);

        if (isset($response['delivery_type'])) {
            $deliveryType = $response['delivery_type'];
            $this->deliveryType = DeliveryType::fromOrderCreateValue($deliveryType);
        }

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
