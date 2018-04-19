<?php

use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('products')->insert([
			'id' => 1001,
			'title' => 'Dreamweaver CS4',
			'author' => 'Janine Warner',
			'publisher' => 'PANEM',
			'price' => 3900,
			'image' => 'image/Product/cs4.jpg'
		]);

		DB::table('products')->insert([
			'id' => 1002,
			'title' => 'JavaScript kliens oldalon',
			'author' => 'Sikos László',
			'publisher' => 'BBS-INFO',
			'price' => 2900,
			'image' => 'image/Product/javascript.jpg'
		]);

		DB::table('products')->insert([
			'id' => 1003,
			'title' => 'Java',
			'author' => 'Barry Burd',
			'publisher' => 'PANEM',
			'price' => 3700,
			'image' => 'image/Product/java.jpg'
		]);

		DB::table('products')->insert([
			'id' => 1004,
			'title' => 'C# 2008',
			'author' => 'Stephen Randy Davis',
			'publisher' => 'PANEM',
			'price' => 3700,
			'image' => 'image/Product/cplusplus.jpg'
		]);

		DB::table('products')->insert([
			'id' => 1005,
			'title' => 'Az Ajax alapjai',
			'author' => 'Joshua Eichorn',
			'publisher' => 'PANEM',
			'price' => 4500,
			'image' => 'image/Product/ajax.jpg'
		]);

		DB::table('products')->insert([
			'id' => 1006,
			'title' => 'Algoritmusok',
			'author' => 'Ivanyos Gábor, Rónyai Lajos, Szabó Réka',
			'publisher' => 'TYPOTEX',
			'price' => 3600,
			'image' => 'image/Product/algoritmusok.jpg'
		]);
	}
}
