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
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'label'      => '10',
                    'amount'     => 10,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'label'      => '20',
                    'amount'     => 20,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'label'      => '30',
                    'amount'     => 30,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'label'      => '40',
                    'amount'     => 40,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'label'      => '50',
                    'amount'     => 50,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'label'      => '100',
                    'amount'     => 100,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
            ]);
        }
    }
}
