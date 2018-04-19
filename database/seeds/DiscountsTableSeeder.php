<?php

use Illuminate\Database\Seeder;

class DiscountsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('discounts')->insert([
			'id' => 101,
			'type' => '10%-os kedvezmény a termék árából',
			'product_id' => 1006,
			'publisher_id' => 0,
			'discount_rate' => 0.1,
			'discount_amount' => 0,
			'products_needed' => 1,
			'product_gets_discount' => 1
		]);

		DB::table('discounts')->insert([
			'id' => 102,
			'type' => 'termék, 500-os kedvezmény a termék árából',
			'product_id' => 1002,
			'publisher_id' => 0,
			'discount_rate' => 0,
			'discount_amount' => 500,
			'products_needed' => 1,
			'product_gets_discount' => 1
		]);

		DB::table('discounts')->insert([
			'id' => 103,
			'type' => '2+1 csomag kedvezmény (a szettben szereplő legolcsóbb termék 100%-os kedvezményt kap)',
			'product_id' => 0,
			'publisher_id' => 1,
			'discount_rate' => 1,
			'discount_amount' => 0,
			'products_needed' => 3,
			'product_gets_discount' => 1
		]);
    }
}
