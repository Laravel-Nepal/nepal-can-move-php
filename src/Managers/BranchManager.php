<?php

declare(strict_types=1);

namespace AchyutN\NCM\Managers;

use AchyutN\NCM\Data\Branch;
use AchyutN\NCM\Exceptions\NCMException;
use Illuminate\Support\Collection;

/** @phpstan-import-type BranchData from Branch */
trait BranchManager
{
    /**
     * Fetch list of NCM branches and details.
     *
     * @return Collection<int, Branch>
     *
     * @throws NCMException
     */
    public function getBranches(): Collection
    {
        /** @var array<BranchData> $response */
        $response = $this->client->get('/v2/branches');

        return collect($response)->map(fn (array $branch): Branch => new Branch($branch, $this));
    }

    /**
     * Get the delivery charge between branches.
     *
     * @throws NCMException
     */
    public function getDeliveryCharge(Branch $source, Branch $destination): float
    {
        /** @var array{'charge': float} $response */
        $response = $this->client->get('/v1/shipping-rate', [
            'creation' => $source->name,
            'destination' => $destination->name,
        ]);

        return $response['charge'];
    }
}
