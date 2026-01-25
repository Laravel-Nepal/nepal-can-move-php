<?php

declare(strict_types=1);

arch('no dd, dump, or ray calls')
    ->expect(['dd', 'dump', 'ray'])
    ->each
    ->not
    ->toBeUsed();

arch('all non-abstract classes are final')
    ->expect('AchyutN\NCM')
    ->classes()
    ->not
    ->abstractClasses()
    ->toBeFinal();

arch('all enums are Enum')
    ->expect('AchyutN\NCM\Enums')
    ->classes()
    ->toBeEnums();
