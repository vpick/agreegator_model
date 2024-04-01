<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\State;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        State::truncate();
  
        $States = [
            [ 'state_name' => 'Andhra Pradesh' ],
            [ 'state_name' => 'Arunachal Pradesh' ],
            [ 'state_name' => 'Assam' ],
            [ 'state_name' => 'Bihar' ],
            [ 'state_name' => 'Chhattisgarh' ],
            [ 'state_name' => 'Goa' ],
            [ 'state_name' => 'Gujarat' ],
            [ 'state_name' => 'Haryana' ],
            [ 'state_name' => 'Himachal Pradesh' ],
            [ 'state_name' => 'Jharkhand' ],
            [ 'state_name' => 'Karnataka' ],
            [ 'state_name' => 'Kerala' ],
            [ 'state_name' => 'Madhya Pradesh' ],
            [ 'state_name' => 'Maharashtra' ],
            [ 'state_name' => 'Manipur' ],
            [ 'state_name' => 'Meghalaya' ],
            [ 'state_name' => 'Mizoram' ],
            [ 'state_name' => 'Nagaland' ],
            [ 'state_name' => 'Odisha' ],
            [ 'state_name' => 'Punjab' ],
            [ 'state_name' => 'Rajasthan' ],
            [ 'state_name' => 'Sikkim' ],
            [ 'state_name' => 'Tamil Nadu' ],
            [ 'state_name' => 'Telangana' ],
            [ 'state_name' => 'Tripura' ],
            [ 'state_name' => 'Uttar Pradesh' ],
            [ 'state_name' => 'Uttarakhand' ],
            [ 'state_name' => 'West Bengal' ],
            [ 'state_name' => 'Andaman and Nicobar Islands' ],
            [ 'state_name' => 'Chandigarh' ],
            [ 'state_name' => 'Dadra & Nagar Haveli and Daman & Diu' ],
            [ 'state_name' => 'Delhi' ],
            [ 'state_name' => 'Jammu and Kashmir' ],
            [ 'state_name' => 'Lakshadweep' ],
            [ 'state_name' => 'Puducherry' ],
            [ 'state_name' => 'Ladakh' ],
        ];
  
        foreach ($States as $key => $state) {
            State::create($state);
        }
    }
}
