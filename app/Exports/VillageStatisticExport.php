<?php

namespace App\Exports;

use App\Models\VillageStatisticCategory;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class VillageStatisticExport implements WithMultipleSheets
{
    public function __construct(
        protected Collection $categories,
    ) {}

    public function sheets(): array
    {
        return $this->categories
            ->map(fn (VillageStatisticCategory $category) => new VillageStatisticCategorySheet($category))
            ->all();
    }
}
