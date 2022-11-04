<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $users = DB::select('SELECT u.id, u.cif, account_id FROM users u INNER JOIN accounts a ON u.cif = a.cif WHERE u.blocked = ? AND a.blocked = ?', [false, false]);

        $user = fake()->randomElement($users);
        //dd($data->cif, $data->id, $data->account_id);

        $accounts = Account::where('blocked', 'false')->where('account_id', '<>', $user->account_id)->pluck('account_id')->toArray();

        return [
            'reference' => 'ref' . fake()->randomNumber(6, true),
            'cif' => $user->cif,
            'debit_account' => $user->account_id,
            'credit_account' => fake()->randomElement($accounts),
            'recipient_name' => fake()->name(),
            'description' => fake()->realText(fake()->numberBetween(10, 20)),
            'transaction_type' => fake()->randomElement(['MTN','AIRTEL']),
            'amount' => fake()->randomNumber(5, true),
            'created_by' => $user->id,
            'client_ip' => '172.217.22.14',
            'status' => 'Successful',
            'reason' => 'Transaction processed successfully',
        ];

        // $table->enum('reversal_required', ['Y', 'N'])->default('N');
        // $table->enum('reversed', ['Y','N'])->nullable();
        // $table->string('reversal_time')->nullable();
        // $table->string('reversal_status')->nullable();
        // $table->string('reversal_message')->nullable();
        // $table->unsignedBigInteger('created_by');
        // $table->timestamps();
        // $table->softDeletes();
    }

    public function failed()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'FAILED',
                'reason' => 'Insufficient funds',
            ];
        });
    }

    public function reversed()
    {
        return $this->state(function (array $attributes) {
            return [
                'retries' => '5',
                'reversal_required' => true,
                'reversed' => true,
                'reversal_time' => now(),
                'reversal_status' => 'SUCCESS',
                'reversal_message' => 'Transaction reversed successfully',
            ];
        });
    }
}
