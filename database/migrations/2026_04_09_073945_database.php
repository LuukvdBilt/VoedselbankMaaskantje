<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->string('street');
            $table->string('house_number', 10);
            $table->string('postal_code', 20);
            $table->string('city');
            $table->timestamps();
            $table->boolean('is_active')->default(true);
            $table->string('note')->nullable();
        });

        Schema::create('people', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone', 50);
            $table->foreignId('address_id')->constrained('addresses');
            $table->timestamps();
            $table->boolean('is_active')->default(true);
            $table->string('note')->nullable();
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
            $table->boolean('is_active')->default(true);
            $table->string('note')->nullable();
        });

          Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('contact_person');
            $table->foreignId('person_id')->constrained('people');
            $table->timestamps();
            $table->boolean('is_active')->default(true);
            $table->string('note')->nullable();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('barcode', 100)->unique();
            $table->string('product_name');
            $table->foreignId('category_id')->nullable()->constrained('categories');
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers');
            $table->timestamps();
            $table->boolean('is_active')->default(true);
            $table->string('note')->nullable();
        });

        Schema::create('allergies', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('description');
            $table->timestamps();
            $table->boolean('is_active')->default(true);
            $table->string('note')->nullable();
        });

        Schema::create('food_packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->string('composition');
            $table->timestamps();
            $table->boolean('is_active')->default(true);
            $table->string('note')->nullable();
        });

        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products');
            $table->integer('quantity')->default(0);
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers');
            $table->foreignId('food_package_id')->nullable()->constrained('food_packages');
            $table->dateTime('expiration_date')->nullable();
            $table->timestamps();
            $table->boolean('is_active')->default(true);
            $table->string('note')->nullable();
        });

        Schema::create('food_package_allergies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('food_package_id')->constrained('food_packages');
            $table->foreignId('allergies_id')->constrained('allergies');
            $table->timestamps();
            $table->boolean('is_active')->default(true);
            $table->string('note')->nullable();
        });

        Schema::create('households', function (Blueprint $table) {
            $table->id();
            $table->foreignId('person_id')->constrained('people');
            $table->integer('total_members')->default(1);
            $table->dateTime('registration_date');
            $table->timestamps();
            $table->boolean('is_active')->default(true);
            $table->string('note')->nullable();
        });

        Schema::create('household_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('household_id')->constrained('households');
            $table->foreignId('person_id')->constrained('people');
            $table->string('relation', 50);
            $table->date('date_of_birth')->nullable();
            $table->timestamps();
            $table->boolean('is_active')->default(true);
            $table->string('note')->nullable();
        });

        Schema::create('food_package_distributions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('household_id')->constrained('households');
            $table->foreignId('food_package_id')->constrained('food_packages');
            $table->dateTime('distribution_date');
            $table->foreignId('volunteer_id')->nullable()->constrained('people');
            $table->timestamps();
            $table->boolean('is_active')->default(true);
            $table->string('note')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('food_package_distributions');
        Schema::dropIfExists('household_members');
        Schema::dropIfExists('households');
        Schema::dropIfExists('food_package_allergies');
        Schema::dropIfExists('inventories');
        Schema::dropIfExists('food_packages');
        Schema::dropIfExists('allergies');
        Schema::dropIfExists('suppliers');
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('people');
        Schema::dropIfExists('addresses');
    }
};
