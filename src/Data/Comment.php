<?php

declare(strict_types=1);

namespace LaravelNepal\NCM\Data;

use Illuminate\Support\Carbon;

/**
 * @phpstan-type CommentData array{
 *     pk: int,
 *     orderid: int,
 *     comments: string,
 *     addedBy: string,
 *     added_time: string
 * }
 *
 * @template-extends BaseData<CommentData>
 */
final class Comment extends BaseData
{
    public int $id;

    public int $orderid;

    public string $comments;

    public string $addedBy;

    public Carbon $addedTime;

    protected function fromResponse(array $response): void
    {
        $this->id = $response['pk'];
        $this->orderid = $response['orderid'];
        $this->comments = $response['comments'];
        $this->addedBy = $response['addedBy'];
        $this->addedTime = Carbon::parse($response['added_time']);
    }
}
