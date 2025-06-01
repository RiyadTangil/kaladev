<?php

namespace App\Observers;

use App\Models\Item;
use App\Traits\DefaultAccessModelTrait;

class ItemObserver
{
    use DefaultAccessModelTrait;

    public function creating(Item $item)
    {
        $item->branch_id = $this->setBranch($item->branch_id);
    }

    public function updating(Item $item)
    {
        $item->branch_id = $this->setBranch($item->branch_id);
    }
}