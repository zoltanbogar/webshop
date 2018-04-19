<?php

use Illuminate\Database\Seeder;

class PublishersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('publishers')->insert([
			'id' => 1,
			'name' => 'PANEM',
		]);

		DB::table('publishers')->insert([
			'id' => 2,
			'name' => 'BBS-INFO',
		]);

		DB::table('publishers')->insert([
			'id' => 3,
			'name' => 'TYPOTEX',
		]);
    }
}
