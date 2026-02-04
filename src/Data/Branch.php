<?php

declare(strict_types=1);

namespace AchyutN\NCM\Data;

/**
 * @phpstan-type BranchData array{
 *      pk: int,
 *      code: string,
 *      name: string,
 *      geocode: string,
 *      address: string,
 *      surcharge: string,
 *      phone: string,
 *      branch_type: string,
 *      areas_covered: string|null,
 *      phone2: string|null,
 *      province_name: string,
 *      district_name: string
 * }
 *
 * @template-extends BaseData<BranchData>
 */
final class Branch extends BaseData
{
    /**
     * The unique ID of the branch.
     */
    public int $id;

    /**
     * The code of the branch.
     */
    public string $code;

    /**
     * The name of the branch.
     */
    public string $name;

    /**
     * The geocode of the branch.
     *
     * @var array<'latitude'|'longitude', float>
     */
    public array $coordinates;

    /**
     * The address of the branch.
     */
    public ?string $address;

    /**
     * The surcharge of the branch.
     */
    public float $surcharge;

    /**
     * The primary phone number of the branch.
     */
    public string $phone;

    /**
     * The secondary phone number of the branch.
     */
    public ?string $phone2 = null;

    /**
     * The type of the branch.
     */
    public string $branchType;

    /**
     * The areas covered by the branch.
     *
     * @var array<int, string>
     */
    public array $areasCovered;

    /**
     * The province name where the branch is located.
     */
    public string $provinceName;

    /**
     * The district name where the branch is located.
     */
    public string $districtName;

    protected function fromResponse(array $response): void
    {
        $this->id = $response['pk'];
        $this->code = $response['code'];
        $this->name = $response['name'];
        [$latitude, $longitude] = $response['geocode'] ? array_map(trim(...), explode(',', $response['geocode'])) : [null, null];
        $this->coordinates = [
            'latitude' => $latitude ? (float) trim($latitude) : 0.0,
            'longitude' => $longitude ? (float) trim($longitude) : 0.0,
        ];
        $this->address = $response['address'];
        $this->surcharge = (float) $response['surcharge'];
        $this->phone = $response['phone'];
        $this->phone2 = $response['phone2'] ?? null;
        $this->branchType = $response['branch_type'];
        $this->areasCovered = isset($response['areas_covered']) ? array_map(trim(...), explode(',', $response['areas_covered'])) : [];
        $this->provinceName = $response['province_name'];
        $this->districtName = $response['district_name'];
    }
}
