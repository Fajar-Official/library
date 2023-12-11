<?php

namespace Database\Seeders;


// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\AuthorSeeder;
use Database\Seeders\CatalogSeeder;
use Database\Seeders\MemberSeeder;
use Database\Seeders\PublisherSeeder;
use Database\Seeders\BookSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UserSeeder::class);
        $this->call(AuthorSeeder::class);
        $this->call(CatalogSeeder::class);
        $this->call(MemberSeeder::class);
        $this->call(PublisherSeeder::class);
        $this->call(BookSeeder::class);
    }
}
