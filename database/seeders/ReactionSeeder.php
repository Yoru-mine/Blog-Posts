<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Reaction;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reactions = ['like', 'burn', 'lol', 'sad'];
        $users = User::all();
        $comments = Comment::all();

        if ($users->isEmpty() || $comments->isEmpty()) {
            $this->command->warn('Нет пользователей или комментариев. Пропускаю создание реакций.');

            return;
        }

        foreach ($comments->take(10) as $comment) {
            $randomUsers = $users->random(min(rand(0, 5), $users->count()));

            foreach ($randomUsers as $user) {
                $reaction = $reactions[array_rand($reactions)];

                $existingReaction = Reaction::where('user_id', $user->id)
                    ->where('reactable_type', Comment::class)
                    ->where('reactable_id', $comment->id)
                    ->first();

                if (! $existingReaction) {
                    Reaction::create([
                        'user_id' => $user->id,
                        'reaction' => $reaction,
                        'reactable_type' => Comment::class,
                        'reactable_id' => $comment->id,
                    ]);
                }
            }
        }

        $this->command->info('✓ Реакции успешно добавлены!');
    }
}
