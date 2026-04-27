<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CommentSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $posts = DB::table('posts')->orderBy('id')->get(['id']);
        $users = DB::table('users')->orderBy('id')->get(['id', 'name', 'email']);
        $now = now();

        for ($i = 0; $i < 25; $i++) {
            $post = $posts[$i % $posts->count()];
            $user = $users[$i % $users->count()];
            $isGuest = $i % 2 === 0;

            $email = $isGuest ? sprintf('guest.comment%02d@contoh.com', $i + 1) : $user->email;
            $name = $isGuest ? $faker->name() : $user->name;

            DB::table('comments')->updateOrInsert(
                [
                    'post_id' => $post->id,
                    'email' => $email,
                ],
                [
                    'user_id' => $isGuest ? null : $user->id,
                    'name' => $name,
                    'email' => $email,
                    'content' => 'Komentar dummy #' . ($i + 1) . ' untuk mengisi diskusi pada artikel blog dan menguji tampilan daftar komentar.',
                    'created_at' => $now->copy()->subHours(25 - $i),
                    'updated_at' => $now->copy()->subHours(25 - $i),
                ]
            );
        }
    }
}
