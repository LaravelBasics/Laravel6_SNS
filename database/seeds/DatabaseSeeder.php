<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        // $this->call(LikesTableSeeder::class);
        //下記を直接mysqlのlikesテーブルで記述
//  INSERT INTO likes (user_id, article_id, created_at, updated_at) VALUES (1, 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);
// sql記述これでハートマークが赤くなる
    }
}
