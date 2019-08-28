<?php

use Illuminate\Database\Seeder;
use App\Models\Payment;

class PaymentsDetailsTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Payment::create([
            'purchase_order_id' => 1,
            'paid_off' => 10000,
            'payment_date' => now()
        ]);

        Payment::create([
            'purchase_order_id' => 2,
            'paid_off' => 20000,
            'payment_date' => now()
        ]);

        Payment::create([
            'purchase_order_id' => 3,
            'paid_off' => 30000,
            'payment_date' => now()
        ]);

        Payment::create([
            'purchase_order_id' => 4,
            'paid_off' => 40000,
            'payment_date' => now()
        ]);
    }
}
