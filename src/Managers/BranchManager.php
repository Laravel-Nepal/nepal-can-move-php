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
}
