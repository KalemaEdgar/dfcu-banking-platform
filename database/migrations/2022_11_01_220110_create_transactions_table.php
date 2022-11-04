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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('reference');
            $table->string('cif');
            $table->string('debit_account');
            $table->string('credit_account');
            $table->string('recipient_name');
            $table->string('description');
            $table->string('transaction_type');
            $table->string('amount');
            $table->string('client_ip')->nullable();
            $table->string('status')->nullable();
            $table->string('reason')->nullable();
            $table->smallInteger('retries')->default(0);
            // Reversal needed incase the transaction maxes out the retries and is still failing
            $table->boolean('reversal_required')->default(false);
            $table->boolean('reversed')->default(false);
            $table->string('reversal_time')->nullable();
            $table->string('reversal_status')->nullable();
            $table->string('reversal_message')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->timestamp('request_time')->useCurrent();
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
        Schema::dropIfExists('transactions');
    }
};
