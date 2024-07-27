<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = User::factory()->count(5)->create();

        foreach ($users as $user) {
            DB::connection('eg2')->table('posts')->insert(Post::factory()->count(5)->make(['user_id' => $user->id])->toArray());
            DB::connection('sa2')->table('posts')->insert(Post::factory()->count(5)->make(['user_id' => $user->id])->toArray());
            DB::connection('in2')->table('posts')->insert(Post::factory()->count(5)->make(['user_id' => $user->id])->toArray());
        }
        
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
