<?php

namespace Database\Seeders;


use App\Models\Address;
use App\Models\Author;
use App\Models\Book;
use App\Models\Genre;
use App\Models\Item;
use App\Models\ItemType;
use App\Models\Language;
use App\Models\OrderStatus;
use App\Models\Publisher;
use App\Models\User;
use App\Models\City;
use App\Models\Country;
use App\Models\UserType;
use App\Models\AuthorBook;
use Database\Factories\BookFactory;
use Database\Factories\OrderFactory;
use Database\Factories\AuthorBookFactory;
use Database\Factories\GenreBookFactory;
use Database\Factories\CountryFactory;
use Database\Factories\CityFactory;
use Database\Factories\AddressFactory;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        ItemType::factory()->create();
        Language::factory()->create();
        OrderStatus::factory()->count(5)->create();

        UserType::factory()->create();
        UserType::factory()->admin()->create();

        User::factory()->count(7)->create();
        User::factory()->count(3)->admin()->create();

        Address::factory()->count(User::all()->count());

        Author::factory()->count(10)->create();
        Publisher::factory()->count(10)->create();
        Genre::factory()->count(10)->create();

        Book::factory()->count(10)->create();
     
        BookFactory::new()->count(5)
            ->hasPublisher()
            ->create();

        $this->call([
            AuthorBookSeeder::class,
            GenreBookSeeder::class
        ]);

        Country::factory()->count(5)->create();

        City::factory()->count(15)->create();

        Address::factory()->count(20)->create();
    }
}
