<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use TheBachtiarz\EAV\Interfaces\Models\EavInterface;
use TheBachtiarz\EAV\Models\Eav;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(EavInterface::TABLE_NAME, function (Blueprint $table) {
            $table->ulid((new Eav())->getPrimaryKeyAttribute())->primary();
            $table->string(EavInterface::ATTRIBUTE_ENTITY)->nullable(false)->index();
            $table->string(EavInterface::ATTRIBUTE_ENTITY_ID)->nullable(false);
            $table->string(EavInterface::ATTRIBUTE_NAME)->nullable(false);
            $table->text(EavInterface::ATTRIBUTE_VALUE);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(EavInterface::TABLE_NAME);
    }
};
