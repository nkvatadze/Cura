<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        if ($users->isEmpty()) {
            return;
        }

        // Create some sample posts
        $posts = [
            'Welcome to our new property management platform! We\'re excited to share updates about our latest developments and community news.',
            'Important announcement: The new swimming pool will be opening next week. All residents are invited to the grand opening ceremony.',
            'Maintenance schedule update: Please note that the elevator maintenance has been rescheduled to next Tuesday.',
            'Community event: Join us for the monthly residents\' meeting this Saturday at 2 PM in the community hall.',
            'Security reminder: Please ensure all doors are properly locked and report any suspicious activity to the security desk.',
            'New amenities: We\'ve added a fitness center and a children\'s play area. Check them out!',
            'Parking update: Additional parking spaces are now available in the basement level.',
            'Weather alert: Heavy rain expected this weekend. Please secure any outdoor furniture.',
            'Welcome new residents: We\'re happy to welcome 15 new families to our community this month.',
            'Feedback needed: We\'re planning to add more green spaces. Share your ideas with us!',
        ];

        foreach ($posts as $index => $description) {
            $post = Post::create([
                'user_id' => $users->random()->id,
                'description' => $description,
                'pinned_at' => $index < 2 ? now()->subDays($index) : null, // First 2 posts are pinned
            ]);
        }
    }
}
