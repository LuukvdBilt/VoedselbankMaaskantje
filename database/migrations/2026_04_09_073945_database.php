<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Address', function (Blueprint $table) {
            $table->id('Id');
            $table->string('Street', 255);
            $table->integer('HouseNumber');
            $table->string('Addition', 10)->nullable();
            $table->string('PostalCode', 40);
            $table->string('City', 100);
            $table->timestamps();
            $table->boolean('is_active')->default(true);
            $table->string('note')->nullable();
        });

        Schema::create('Contact', function (Blueprint $table) {
            $table->id('Id');
            $table->unsignedBigInteger('UserId');
            $table->string('FirstName', 100);
            $table->string('LastName', 100);
            $table->string('Phone', 20)->nullable();
            $table->unsignedBigInteger('AddressId')->nullable();
            $table->timestamps();
            $table->boolean('is_active')->default(true);
            $table->string('note')->nullable();

            $table->foreign('UserId')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('AddressId')->references('Id')->on('Address')->onDelete('cascade');
        });

        Schema::create('Client', function (Blueprint $table) {
            $table->id('Id');
            $table->string('FirstName', 100);
            $table->string('LastName', 100);
            $table->string('Phone', 20)->nullable();
            $table->unsignedBigInteger('AddressId')->nullable();
            $table->timestamps();
            $table->boolean('is_active')->default(true);
            $table->string('note')->nullable();

            $table->foreign('AddressId')->references('Id')->on('Address')->onDelete('cascade');
        });

        Schema::create('Category', function (Blueprint $table) {
            $table->id('Id');
            $table->string('Name', 100)->unique();
            $table->timestamps();
            $table->boolean('is_active')->default(true);
            $table->string('note')->nullable();
        });

        Schema::create('Supplier', function (Blueprint $table) {
            $table->id('Id');
            $table->string('CompanyName', 255);
            $table->unsignedBigInteger('ContactId');
            $table->timestamps();
            $table->boolean('is_active')->default(true);
            $table->string('note')->nullable();

            $table->foreign('ContactId')->references('Id')->on('Contact')->onDelete('cascade');
        });

        Schema::create('Product', function (Blueprint $table) {
            $table->id('Id');
            $table->string('Barcode', 100)->unique();
            $table->string('ProductName');
            $table->unsignedBigInteger('CategoryId');
            $table->unsignedBigInteger('SupplierId');
            $table->timestamps();
            $table->boolean('is_active')->default(true);
            $table->string('note')->nullable();

            $table->foreign('CategoryId')->references('Id')->on('Category')->onDelete('cascade');
            $table->foreign('SupplierId')->references('Id')->on('Supplier')->onDelete('cascade');
        });

        Schema::create('Allergies', function (Blueprint $table) {
            $table->id('Id');
            $table->string('Name')->unique();
            $table->string('Description')->nullable();
            $table->timestamps();
            $table->boolean('is_active')->default(true);
            $table->string('note')->nullable();
        });

        Schema::create('FoodPackages', function (Blueprint $table) {
            $table->id('Id');
            $table->string('Name');
            $table->string('Description')->nullable();
            $table->timestamps();
            $table->boolean('is_active')->default(true);
            $table->string('note')->nullable();
        });

        Schema::create('FoodPackage_Product', function (Blueprint $table) {
            $table->id('Id');
            $table->unsignedBigInteger('FoodPackageId');
            $table->unsignedBigInteger('ProductId');
            $table->integer('Quantity')->default(1);
            $table->timestamps();
            $table->boolean('is_active')->default(true);
            $table->string('note')->nullable();

            $table->unique(['FoodPackageId', 'ProductId']);
            $table->foreign('FoodPackageId')->references('Id')->on('FoodPackages')->onDelete('cascade');
            $table->foreign('ProductId')->references('Id')->on('Product')->onDelete('cascade');
        });

        Schema::create('FoodPackage_Allergy', function (Blueprint $table) {
            $table->id('Id');
            $table->unsignedBigInteger('FoodPackageId');
            $table->unsignedBigInteger('AllergyId');
            $table->timestamps();
            $table->boolean('is_active')->default(true);
            $table->string('note')->nullable();

            $table->unique(['FoodPackageId', 'AllergyId']);
            $table->foreign('FoodPackageId')->references('Id')->on('FoodPackages')->onDelete('cascade');
            $table->foreign('AllergyId')->references('Id')->on('Allergies')->onDelete('cascade');
        });

        Schema::create('Inventory', function (Blueprint $table) {
            $table->id('Id');
            $table->unsignedBigInteger('ProductId');
            $table->unsignedBigInteger('SupplierId')->nullable();
            $table->integer('Quantity')->default(0);
            $table->dateTime('ExpirationDate')->nullable();
            $table->timestamps();
            $table->boolean('is_active')->default(true);
            $table->string('note')->nullable();

            $table->foreign('ProductId')->references('Id')->on('Product')->onDelete('cascade');
            $table->foreign('SupplierId')->references('Id')->on('Supplier')->onDelete('cascade');
        });

        Schema::create('Household', function (Blueprint $table) {
            $table->id('Id');
            $table->unsignedBigInteger('ClientId');
            $table->integer('TotalMembers')->default(1);
            $table->dateTime('RegistrationDate')->useCurrent();
            $table->timestamps();
            $table->boolean('is_active')->default(true);
            $table->string('note')->nullable();

            $table->foreign('ClientId')->references('Id')->on('Client')->onDelete('cascade');
        });

        Schema::create('HouseholdMember', function (Blueprint $table) {
            $table->id('Id');
            $table->unsignedBigInteger('HouseholdId');
            $table->string('FirstName');
            $table->string('LastName')->nullable();
            $table->string('Relation', 50)->nullable();
            $table->date('DateOfBirth')->nullable();
            $table->timestamps();
            $table->boolean('is_active')->default(true);
            $table->string('note')->nullable();

            $table->foreign('HouseholdId')->references('Id')->on('Household')->onDelete('cascade');
        });

        Schema::create('FoodPackageDistribution', function (Blueprint $table) {
            $table->id('Id');
            $table->unsignedBigInteger('HouseholdId');
            $table->unsignedBigInteger('FoodPackageId');
            $table->unsignedBigInteger('VolunteerId')->nullable();
            $table->dateTime('DistributionDate')->useCurrent();
            $table->timestamps();
            $table->boolean('is_active')->default(true);
            $table->string('note')->nullable();

            $table->foreign('HouseholdId')->references('Id')->on('Household')->onDelete('cascade');
            $table->foreign('FoodPackageId')->references('Id')->on('FoodPackages')->onDelete('cascade');
            $table->foreign('VolunteerId')->references('Id')->on('Contact')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('FoodPackageDistribution');
        Schema::dropIfExists('HouseholdMember');
        Schema::dropIfExists('Household');
        Schema::dropIfExists('Inventory');
        Schema::dropIfExists('FoodPackage_Allergy');
        Schema::dropIfExists('FoodPackage_Product');
        Schema::dropIfExists('FoodPackages');
        Schema::dropIfExists('Allergies');
        Schema::dropIfExists('Product');
        Schema::dropIfExists('Supplier');
        Schema::dropIfExists('Contact');
        Schema::dropIfExists('Client');
        Schema::dropIfExists('Category');
        Schema::dropIfExists('Address');
    }
};
