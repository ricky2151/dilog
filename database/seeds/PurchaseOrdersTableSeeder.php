<?php

use Illuminate\Database\Seeder;
use App\Models\PurchaseOrder;
use Faker\Factory as Faker;

class PurchaseOrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PurchaseOrder::create([
            'supplier_id' => 1,
            'no_po' => "PO-1",
            'finish_date' => date("Y-m-d", mt_rand(1, time())),
            'paid_date' => date("Y-m-d", mt_rand(1, time())),
            'fine' => 1000,
            'payment_type' => 1,
            'type' => 1,
            'payment_terms' => 1,
            'periode_id' => 1,
            'approved_by_user_id' => null,
            'status' => 1,
            'total' => 10000,
            'is_completed' => 0,
            'purchase_request_id' => null,
        ]);

        PurchaseOrder::create([
            'supplier_id' => 2,
            'no_po' => "PO-2",
            'finish_date' => date("Y-m-d", mt_rand(1, time())),
            'paid_date' => date("Y-m-d", mt_rand(1, time())),
            'fine' => 2000,
            'payment_type' => 2,
            'type' => 2,
            'payment_terms' => 2,
            'periode_id' => 2,
            'approved_by_user_id' => null,
            'status' => 2,
            'total' => 20000,
            'is_completed' => 0,
            'purchase_request_id' => 1,
        ]);

        PurchaseOrder::create([
            'supplier_id' => 3,
            'no_po' => "PO-3",
            'finish_date' => date("Y-m-d", mt_rand(1, time())),
            'paid_date' => date("Y-m-d", mt_rand(1, time())),
            'fine' => 3000,
            'payment_type' => 2,
            'type' => 1,
            'payment_terms' => 1,
            'periode_id' => 1,
            'approved_by_user_id' => 1,
            'status' => 3,
            'total' => 30000,
            'is_completed' => 0,
            'purchase_request_id' => null,
        ]);

        PurchaseOrder::create([
            'supplier_id' => 4,
            'no_po' => "PO-4",
            'finish_date' => date("Y-m-d", mt_rand(1, time())),
            'paid_date' => date("Y-m-d", mt_rand(1, time())),
            'fine' => 4000,
            'payment_type' => 1,
            'type' => 1,
            'payment_terms' => 1,
            'periode_id' => 1,
            'approved_by_user_id' => 4,
            'status' => 4,
            'total' => 40000,
            'is_completed' => 100,
            'purchase_request_id' => null,
        ]);
    }
}
