<?php

use Illuminate\Database\Seeder;
use App\Models\Supplier;

class SuppliersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Supplier::create([
            "name_company" => "Company 1",
            "name_owner" => "Owner 1",
            "name_pic" => "Pic 1",
            "name_sales" => "Sales 1",
            "address" => "Address 1",
            "no_telp_company" => "telp company 1",
            "no_telp_owner" => "temp owner 1",
            "email" => "sup1@email.com",
            "fax" => "fax 1",
            "npwp" => "npwp 1",
            "no_rek" => "rekening 1",
        ]);

        Supplier::create([
            "name_company" => "Company 2",
            "name_owner" => "Owner 2",
            "name_pic" => "Pic 2",
            "name_sales" => "Sales 2",
            "address" => "Address 2",
            "no_telp_company" => "telp company 2",
            "no_telp_owner" => "temp owner 2",
            "email" => "sup2@email.com",
            "fax" => "fax 2",
            "npwp" => "npwp 2",
            "no_rek" => "rekening 2",
        ]);

        Supplier::create([
            "name_company" => "Company 3",
            "name_owner" => "Owner 3",
            "name_pic" => "Pic 3",
            "name_sales" => "Sales 3",
            "address" => "Address 3",
            "no_telp_company" => "telp company 3",
            "no_telp_owner" => "temp owner 3",
            "email" => "sup3@email.com",
            "fax" => "fax 3",
            "npwp" => "npwp 3",
            "no_rek" => "rekening 3",
        ]);

        Supplier::create([
            "name_company" => "Company 4",
            "name_owner" => "Owner 4",
            "name_pic" => "Pic 4",
            "name_sales" => "Sales 4",
            "address" => "Address 4",
            "no_telp_company" => "telp company 4",
            "no_telp_owner" => "temp owner 4",
            "email" => "sup4@email.com",
            "fax" => "fax 4",
            "npwp" => "npwp 4",
            "no_rek" => "rekening 4W",
        ]);
    }
}
