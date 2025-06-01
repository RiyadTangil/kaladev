<?php

namespace App\Exports;

use App\Http\Requests\PaginateRequest;
use App\Libraries\AppLibrary;
use App\Services\OfferService;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OfferExport implements FromCollection, WithHeadings
{

    public OfferService $offerService;
    public PaginateRequest $request;

    public function __construct(OfferService $offerService, $request)
    {
        $this->offerService = $offerService;
        $this->request      = $request;
    }

    public function collection(): \Illuminate\Support\Collection
    {
        $offerArray  = [];
        $offersArray = $this->offerService->list($this->request);

        foreach ($offersArray as $offer) {
            $offerArray[] = [
                $offer->name,
                $offer->amount,
                trans('day.' . $offer->day),
                AppLibrary::time($offer->start_time),
                AppLibrary::time($offer->end_time),
                trans('statuse.' . $offer->status),
            ];
        }
        return collect($offerArray);
    }

    public function headings(): array
    {
        return [
            trans('all.label.name'),
            trans('all.label.amount'),
            trans('all.label.day'),
            trans('all.label.start_time'),
            trans('all.label.end_time'),
            trans('all.label.status'),
        ];
    }
}