<?php

namespace App\Exports;

use App\Models\VillageStatisticCategory;
use App\Models\VillageStatisticItem;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class VillageStatisticCategorySheet implements FromArray, ShouldAutoSize, WithHeadings, WithStyles, WithTitle
{
    public function __construct(
        protected VillageStatisticCategory $category,
    ) {}

    public function title(): string
    {
        // Excel membatasi nama sheet 31 karakter dan melarang beberapa karakter khusus.
        $title = preg_replace('/[\\\\\/\?\*\[\]:]/', '-', $this->category->name);

        return mb_substr($title, 0, 31);
    }

    public function headings(): array
    {
        return array_column($this->category->columnDefinitions(), 'label');
    }

    public function array(): array
    {
        $columns = $this->category->columnDefinitions();

        return $this->category->activeItems
            ->map(function (VillageStatisticItem $item) use ($columns) {
                return array_map(function (array $column) use ($item) {
                    if (($column['type'] ?? 'text') === 'number') {
                        return $item->chartValueFor($column['key']) ?? $item->valueFor($column['key']);
                    }

                    return $item->valueFor($column['key']);
                }, $columns);
            })
            ->all();
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
