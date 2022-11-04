<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('cif');
            $table->string('account_id')->unique();
            $table->bigInteger('balance');
            $table->timestamp('last_transacted_at')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->boolean('blocked')->default(false);
            $table->timestamp('blocked_at')->nullable();
            $table->string('blocked_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accounts');
    }
};
