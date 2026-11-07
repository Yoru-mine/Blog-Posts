<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::whereIn('email', [
            'admin@example.com',
            'alice@example.com',
            'mia@example.com',
            'noah@example.com',
            'ethan@example.com',
        ])->get()->keyBy('email');

        $tagsData = ['Lifestyle', 'Nature', 'Gaming', 'Personal', 'Health', 'City', 'Atmosphere', 'Tech', 'Study', 'Weekend'];
        $tags = [];
        foreach ($tagsData as $tagName) {
            $tags[$tagName] = Tag::firstOrCreate(['name' => $tagName], ['slug' => strtolower($tagName)]);
        }

        $posts = [
            ['email' => 'alice@example.com', 'title' => 'Valentines Day', 'content' => 'A small personal note about soft colors, quiet feelings, and one of the calmest days of the year.', 'category_name' => 'Life', 'tags' => ['Lifestyle', 'Personal', 'Atmosphere']],
            ['email' => 'mia@example.com', 'title' => 'A walk in the park', 'content' => 'An afternoon walk, cold air, empty paths, and the kind of silence that makes everything feel cinematic.', 'category_name' => 'Life', 'tags' => ['Nature', 'Atmosphere']],
            ['email' => 'noah@example.com', 'title' => 'Playing Minecraft', 'content' => 'A late evening session with glowing textures, dark caves, and a strangely cozy atmosphere.', 'category_name' => 'Technologies', 'tags' => ['Gaming', 'Tech']],
            ['email' => 'ethan@example.com', 'title' => 'Long-awaited birthday', 'content' => 'A post about waiting for an important date, planning little details, and finally reaching that moment.', 'category_name' => 'Life', 'tags' => ['Personal', 'Lifestyle']],
            ['email' => 'admin@example.com', 'title' => 'Healthy eating', 'content' => 'Simple food, balanced routine, and a reminder that everyday habits shape the feeling of the whole week.', 'category_name' => 'Life', 'tags' => ['Health', 'Lifestyle']],
            ['email' => 'alice@example.com', 'title' => 'Night bus window', 'content' => 'City lights reflected on cold glass, a quiet playlist, and the feeling of watching the whole night move past you.', 'category_name' => 'Life', 'tags' => ['City', 'Atmosphere']],
            ['email' => 'mia@example.com', 'title' => 'After rain streets', 'content' => 'Wet asphalt, blurred reflections, and the strange comfort that comes after a heavy evening rain.', 'category_name' => 'Life', 'tags' => ['Nature', 'City', 'Atmosphere']],
            ['email' => 'noah@example.com', 'title' => 'Small room setup', 'content' => 'A simple desk, soft monitor light, and the kind of setup that feels more personal than expensive.', 'category_name' => 'Design', 'tags' => ['Tech', 'Design']],
            ['email' => 'ethan@example.com', 'title' => 'Morning lecture', 'content' => 'Half-awake students, grey daylight, and the quiet routine of showing up even when the day starts too early.', 'category_name' => 'Development', 'tags' => ['Study', 'Personal']],
            ['email' => 'admin@example.com', 'title' => 'Late coffee', 'content' => 'Not the healthiest habit, but sometimes a late cup of coffee feels like part of the whole atmosphere.', 'category_name' => 'Life', 'tags' => ['Health', 'Lifestyle']],
            ['email' => 'alice@example.com', 'title' => 'Waiting at the station', 'content' => 'A pause between places, train sounds in the distance, and a few quiet minutes to think about everything.', 'category_name' => 'Life', 'tags' => ['City', 'Atmosphere']],
            ['email' => 'mia@example.com', 'title' => 'Soft winter day', 'content' => 'No dramatic snowstorm, just pale light, cold air, and a slow day that felt gentler than expected.', 'category_name' => 'Life', 'tags' => ['Nature', 'Atmosphere']],
            ['email' => 'noah@example.com', 'title' => 'Computer club memories', 'content' => 'Old games, noisy keyboards, and the kind of shared space that becomes part of growing up without warning.', 'category_name' => 'Memes', 'tags' => ['Gaming', 'Tech']],
            ['email' => 'ethan@example.com', 'title' => 'Package arrived', 'content' => 'A tiny event, but still enough to make the whole day feel a little brighter and more exciting.', 'category_name' => 'Life', 'tags' => ['Personal']],
            ['email' => 'admin@example.com', 'title' => 'Quiet weekend', 'content' => 'No plans, no noise, just a slower pace and enough space to let the mind settle down a little.', 'category_name' => 'Life', 'tags' => ['Weekend', 'Lifestyle']],
        ];

        foreach ($posts as $index => $postData) {
            $user = $users[$postData['email']] ?? null;
            $category = Category::where('name', $postData['category_name'])->first();

            if (! $user || ! $category) {
                continue;
            }

            $imagePath = 'images/' . ($index + 1) . '.jpg';

            $post = Post::updateOrCreate(
                ['user_id' => $user->id, 'title' => $postData['title']],
                [
                    'content' => $postData['content'],
                    'category_id' => $category->id,
                    'image' => $imagePath,
                    'is_published' => true,
                    'visibility' => 'public',
                ]
            );

            if (method_exists($post, 'tags')) {
                $tagIds = array_filter(array_map(fn($t) => $tags[$t]->id ?? null, $postData['tags']));
                $post->tags()->sync($tagIds);
            }
        }
    }
}
