<?php

namespace Database\Seeders;


use App\Models\RiderTip;
use Dipokhalder\EnvEditor\EnvEditor;
use Illuminate\Database\Seeder;


class RiderTipTableSeeder extends Seeder
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
            RiderTip::insert([
                [
                    'label'      => 'No Tip',
                    'amount'     => 0,
                    'type'       => 'percentage',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'label'      => '5%',
                    'amount'     => 5,
                    'type'       => 'percentage',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'label'      => '10%',
                    'amount'     => 10,
                    'type'       => 'percentage',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'label'      => '15%',
                    'amount'     => 15,
                    'type'       => 'percentage',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'label'      => '20%',
                    'amount'     => 20,
                    'type'       => 'percentage',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
            ]);
        }
    }
}
