<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    private $tableName = "ticket";
    public function up(): void
    {
        if (!Schema::hasTable($this->tableName)) {
            Schema::create($this->tableName, function (Blueprint $table) {
                $table->id();
                $table->string('client_id')->comment('айди клиента');
                $table->string('topic')->comment('тема заявки');
                $table->string('description')->comment('описание заявки');
                $table->tinyInteger('status')->comment('статус заявки');
                $table->date('response_date')->comment('дата ответа');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer');
    }
};
