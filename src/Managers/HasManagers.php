<?php

declare(strict_types=1);

namespace AchyutN\NCM\Managers;

trait HasManagers
{
    use BranchManager;
    use OrderManager;
    use TicketManager;
}
