<?php

namespace Database\Seeders;

use App\Models\Offer;
use Dipokhalder\EnvEditor\EnvEditor;
use Illuminate\Database\Seeder;
use App\Enums\Status;
use Carbon\Carbon;
use Illuminate\Support\Str;

class OfferTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $envService = new EnvEditor();
        if ($envService->getValue('DEMO')) {
            $offers = [
                [
                    'name'       => 'Savory and Satisfying',
                    'slug'       => Str::slug('Savory and Satisfying'),
                    'amount'     => '5.00',
                    'status'     => Status::ACTIVE,
                    'day'        => 1,
                    'start_time' => '10:00:00',
                    'end_time'   => '21:59:59',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'name'       => 'Uplifting Anytime',
                    'slug'       => Str::slug('Uplifting Anytime'),
                    'amount'     => '7.00',
                    'status'     => Status::ACTIVE,
                    'day'        => 2,
                    'start_time' => '10:00:00',
                    'end_time'   => '21:59:59',
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            ];

            foreach ($offers as $offer) {
                $offerObject = Offer::create($offer);
                if (file_exists(
                    public_path('/images/seeder/offer/' . strtolower(str_replace(' ', '_', $offer['name'])) . '.png')
                )) {
                    $offerObject->addMedia(
                        public_path(
                            '/images/seeder/offer/' . strtolower(str_replace(' ', '_', $offer['name'])) . '.png'
                        )
                    )->preservingOriginal()->toMediaCollection('offer');
                }
            }
        }
    }
}