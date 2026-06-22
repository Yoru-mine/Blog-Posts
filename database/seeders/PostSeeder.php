<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

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

        // 1. Scan public/images and copy allowed images to posts storage
        $imagesPath = public_path('images');
        $allowedImages = [];

        if (File::isDirectory($imagesPath)) {
            $files = File::files($imagesPath);
            
            // Exclude list (lowercase names without extension)
            $excludeNames = [
                'es', 
                'eye 1', 
                'fon', 
                'gradient-back', 
                'images', 
                'klava', 
                'klava2', 
                'knopka', 
                'mv1',
                'default-avatar'
            ];
            
            foreach ($files as $file) {
                $nameWithoutExtension = pathinfo($file->getFilename(), PATHINFO_FILENAME);
                $extension = strtolower($file->getExtension());
                
                // Only allow common image extensions
                if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'])) {
                    if (!in_array(strtolower($nameWithoutExtension), $excludeNames)) {
                        $filename = $file->getFilename();
                        $allowedImages[] = $filename;
                        
                        // Copy to storage disk 'public' under 'posts' folder
                        $fileContent = File::get($file->getRealPath());
                        Storage::disk('public')->put('posts/' . $filename, $fileContent);
                    }
                }
            }
        }

        // Title to preferred image name mapping
        $titleToImage = [
            'Valentines Day' => 'eyes.png',
            'A walk in the park' => 'rest.png',
            'Playing Minecraft' => 'minecraftt.png',
            'Long-awaited birthday' => 'calendar.png',
            'Healthy eating' => 'have lunch.png',
            'Night bus window' => 'yorru.png',
            'After rain streets' => 'yoru.jpg',
            'Small room setup' => 'compclub.png',
            'Morning lecture' => 'yoru.jpg',
            'Late coffee' => 'have lunch.png',
            'Waiting at the station' => 'station1.png',
            'Soft winter day' => 'rest.png',
            'Computer club memories' => 'compclub.png',
            'Package arrived' => 'calendar.png',
            'Quiet weekend' => 'rest.png',
        ];

        $posts = [
            [
                'email' => 'alice@example.com',
                'title' => 'Valentines Day',
                'content' => 'A small personal note about soft colors, quiet feelings, and one of the calmest days of the year.',
            ],
            [
                'email' => 'mia@example.com',
                'title' => 'A walk in the park',
                'content' => 'An afternoon walk, cold air, empty paths, and the kind of silence that makes everything feel cinematic.',
            ],
            [
                'email' => 'noah@example.com',
                'title' => 'Playing Minecraft',
                'content' => 'A late evening session with glowing textures, dark caves, and a strangely cozy atmosphere.',
            ],
            [
                'email' => 'ethan@example.com',
                'title' => 'Long-awaited birthday',
                'content' => 'A post about waiting for an important date, planning little details, and finally reaching that moment.',
            ],
            [
                'email' => 'admin@example.com',
                'title' => 'Healthy eating',
                'content' => 'Simple food, balanced routine, and a reminder that everyday habits shape the feeling of the whole week.',
            ],
            [
                'email' => 'alice@example.com',
                'title' => 'Night bus window',
                'content' => 'City lights reflected on cold glass, a quiet playlist, and the feeling of watching the whole night move past you.',
            ],
            [
                'email' => 'mia@example.com',
                'title' => 'After rain streets',
                'content' => 'Wet asphalt, blurred reflections, and the strange comfort that comes after a heavy evening rain.',
            ],
            [
                'email' => 'noah@example.com',
                'title' => 'Small room setup',
                'content' => 'A simple desk, soft monitor light, and the kind of setup that feels more personal than expensive.',
            ],
            [
                'email' => 'ethan@example.com',
                'title' => 'Morning lecture',
                'content' => 'Half-awake students, grey daylight, and the quiet routine of showing up even when the day starts too early.',
            ],
            [
                'email' => 'admin@example.com',
                'title' => 'Late coffee',
                'content' => 'Not the healthiest habit, but sometimes a late cup of coffee feels like part of the whole atmosphere.',
            ],
            [
                'email' => 'alice@example.com',
                'title' => 'Waiting at the station',
                'content' => 'A pause between places, train sounds in the distance, and a few quiet minutes to think about everything.',
            ],
            [
                'email' => 'mia@example.com',
                'title' => 'Soft winter day',
                'content' => 'No dramatic snowstorm, just pale light, cold air, and a slow day that felt gentler than expected.',
            ],
            [
                'email' => 'noah@example.com',
                'title' => 'Computer club memories',
                'content' => 'Old games, noisy keyboards, and the kind of shared space that becomes part of growing up without warning.',
            ],
            [
                'email' => 'ethan@example.com',
                'title' => 'Package arrived',
                'content' => 'A tiny event, but still enough to make the whole day feel a little brighter and more exciting.',
            ],
            [
                'email' => 'admin@example.com',
                'title' => 'Quiet weekend',
                'content' => 'No plans, no noise, just a slower pace and enough space to let the mind settle down a little.',
            ],
        ];

        foreach ($posts as $index => $post) {
            $user = $users[$post['email']] ?? null;

            if (!$user) {
                continue;
            }

            // Determine individual image path
            $imageName = $titleToImage[$post['title']] ?? null;
            $imagePath = null;

            if ($imageName && in_array($imageName, $allowedImages)) {
                $imagePath = 'posts/' . $imageName;
            } elseif (!empty($allowedImages)) {
                $imagePath = 'posts/' . $allowedImages[$index % count($allowedImages)];
            }

            Post::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'title' => $post['title'],
                ],
                [
                    'content' => $post['content'],
                    'image' => $imagePath,
                ]
            );
        }
    }
}
