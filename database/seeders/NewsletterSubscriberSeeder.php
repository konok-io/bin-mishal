<?php

namespace Database\Seeders;

use App\Models\NewsletterSubscriber;
use Illuminate\Database\Seeder;

class NewsletterSubscriberSeeder extends Seeder
{
    public function run(): void
    {
        $subscribers = [
            ['email' => 'subscriber1@email.com', 'name' => 'John Doe', 'is_verified' => true],
            ['email' => 'subscriber2@email.com', 'name' => 'Jane Smith', 'is_verified' => true],
            ['email' => 'subscriber3@email.com', 'name' => 'Ahmed Khan', 'is_verified' => true],
            ['email' => 'subscriber4@email.com', 'name' => 'Maria Garcia', 'is_verified' => true],
            ['email' => 'subscriber5@email.com', 'name' => 'David Lee', 'is_verified' => true],
            ['email' => 'subscriber6@email.com', 'name' => 'Sarah Brown', 'is_verified' => false],
            ['email' => 'subscriber7@email.com', 'name' => 'Ali Hassan', 'is_verified' => true],
            ['email' => 'subscriber8@email.com', 'name' => 'Fatima Noor', 'is_verified' => true],
        ];

        foreach ($subscribers as $subscriberData) {
            NewsletterSubscriber::updateOrCreate(
                ['email' => $subscriberData['email']],
                [
                    'name' => $subscriberData['name'],
                    'is_active' => true,
                    'is_verified' => $subscriberData['is_verified'],
                    'subscribed_at' => now()->subDays(rand(1, 90)),
                ]
            );
        }

        $this->command->info('NewsletterSubscriberSeeder: Created ' . count($subscribers) . ' newsletter subscribers!');
    }
}
