## Country table migrate

`php artisan make:migration create_countries_table --create=countries`

```
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};

```

## CItyes table migrate

`php artisan make:migration create_cities_table --create=cities`

```
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('country_id');
            $table->string('name');
            $table->timestamps();

            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cities');
    }
};

```

## User table modify

`php artisan make:migration add_extra_fields_to_users_table --table=users`

```
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('gender', ['male', 'female', 'other'])->after('password');
            $table->unsignedBigInteger('country_id')->nullable()->after('gender');
            $table->unsignedBigInteger('city_id')->nullable()->after('country_id');
            $table->enum('role',['user', 'admin'])->default('user')->after('city_id');
            $table->boolean('terms')->default(false)->after('role');

            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
             $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['country_id']);
            $table->dropForeign(['city_id']);
            $table->dropColumn(['gender','country_id','city_id','role','terms']);
        });
    }
};

```

## migrate all table

`php artisan migrate`

## model creation

php artisan make:model User
php artisan make:model Country
php artisan make:model City

## data seed

`php artisan make:seeder CountrySeeder`
`php artisan make:seeder CitySeeder`

```
// database/seeders/CountrySeeder.php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Country;

class CountrySeeder extends Seeder {
    public function run(): void {
        Country::insert([
            ['name'=>'India'],
            ['name'=>'USA'],
            ['name'=>'Canada'],
        ]);
    }
}

```

```
// database/seeders/CitySeeder.php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\City;

class CitySeeder extends Seeder {
    public function run(): void {
        City::insert([
            ['country_id'=>1,'name'=>'Delhi'],
            ['country_id'=>1,'name'=>'Mumbai'],
            ['country_id'=>1,'name'=>'Bangalore'],
            ['country_id'=>2,'name'=>'New York'],
            ['country_id'=>2,'name'=>'Los Angeles'],
            ['country_id'=>3,'name'=>'Toronto'],
            ['country_id'=>3,'name'=>'Vancouver'],
        ]);
    }
}

```

## data seeding in table

php artisan db:seed --class=CountrySeeder
php artisan db:seed --class=CitySeeder

## auth conttroller

`php artisan make:controller Auth/RegisterController`
