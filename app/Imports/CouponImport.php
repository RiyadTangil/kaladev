<?php

namespace App\Imports;

use App\Models\Coupon;
use App\Enums\DiscountType;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class CouponImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure, SkipsEmptyRows
{
    use Importable, SkipsFailures;

    public function model(array $row)
    {
        return new Coupon([
            'name'             => $this->sanitizeInput($row['name'] ?? ''),
            'description'      => $this->sanitizeInput($row['description'] ?? null),
            'code'             => $this->sanitizeInput($row['code'] ?? ''),
            'discount'         => $row['discount'] ?? 0,
            'discount_type'    => $row['discount_type'] ?? DiscountType::PERCENTAGE,
            'start_date'       => $row['start_date'] ?? null,
            'end_date'         => $row['end_date'] ?? null,
            'minimum_order'    => $row['minimum_order'] ?? 0,
            'maximum_discount' => $row['maximum_discount'] ?? 0,
            'limit_per_user'   => $row['limit_per_user'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            'name'        => [
                'required',
                'string',
                'max:190',
                Rule::unique("coupons", "name")
            ],
            'description'      => ['nullable', 'string', 'max:900'],
            'code'             => ['required', 'string', 'max:24', Rule::unique("coupons", "code")],
            'discount'         => ['required', 'numeric'],
            'discount_type'    => ['required', 'numeric', 'max:24'],
            'minimum_order'    => ['required', 'numeric'],
            'maximum_discount' => ['required', 'numeric'],
            'limit_per_user'   => ['nullable', 'numeric'],
            'start_date'       => ['required', 'string'],
            'end_date'         => ['required', 'string'],
        ];
    }

    private function sanitizeInput($value)
    {
        return mb_convert_encoding(trim($value), 'UTF-8', 'UTF-8');
    }
}