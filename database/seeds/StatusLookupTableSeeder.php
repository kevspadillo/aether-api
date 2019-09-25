<?php

use Illuminate\Database\Seeder;

use App\Models\StatusLookup;

class StatusLookupTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        StatusLookup::query()->truncate();
        StatusLookup::create(['status_lookup_name' => 'Pending',  'is_active' => '1' ]);
        StatusLookup::create(['status_lookup_name' => 'Approved', 'is_active' => '1' ]);
        StatusLookup::create(['status_lookup_name' => 'Declined', 'is_active' => '1' ]);
        StatusLookup::create(['status_lookup_name' => 'Inactive', 'is_active' => '1' ]);
    }
}
