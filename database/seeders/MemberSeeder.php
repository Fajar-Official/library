<?php

namespace Database\Seeders;
use Faker\Factory as Faker;
use App\Models\Member;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        for ($i=0; $i < 20; $i++) { 
            $member = new Member;
            
            $member->name = $faker->name;
            $member->gender = rand(0,1);
            $member->email = $faker->email;
            $member->phone_number = '0821' . $faker->randomNumber(8);
            $member->address = $faker->address;
            
            $member->save();
        }
        
    }
}
