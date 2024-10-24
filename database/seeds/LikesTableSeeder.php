<?php
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LikesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('likes')->insert([
            'user_id' => 1,
            'article_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
