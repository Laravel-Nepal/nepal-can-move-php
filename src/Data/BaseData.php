<?php

declare(strict_types=1);

namespace AchyutN\NCM\Data;

use AchyutN\NCM\NCM;

abstract class BaseData
{
    public function __construct(protected array $attributes, protected NCM $ncm) // @phpstan-ignore-line
    {
        $this->fill();
    }

    protected function fill(): void
    {
        foreach ($this->attributes as $key => $value) {
            $key = $this->camelCase($key);
            $this->{$key} = $value;
        }
    }

    protected function camelCase(string $key): string
    {
        return lcfirst(str_replace(' ', '', ucwords(str_replace(['_', '-'], ' ', $key))));
    }
}
