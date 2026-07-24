<?php

namespace Database\Seeders;

use App\Models\ContactMessage;
use Illuminate\Database\Seeder;

class ContactMessageSeeder extends Seeder
{
    public function run(): void
    {
        $messages = [
            [
                'name' => 'John Smith',
                'email' => 'john.smith@email.com',
                'phone' => '+966551234567',
                'subject' => 'Inquiry about Umrah Packages',
                'message' => 'Hello, I would like to know more about your Umrah packages for a family of 4. Please send me the details.',
                'type' => 'general',
                'is_read' => false,
                'ip_address' => '192.168.1.100',
            ],
            [
                'name' => 'Fatima Hassan',
                'email' => 'fatima.hassan@email.com',
                'phone' => '+966552345678',
                'subject' => 'Flight Booking Issue',
                'message' => 'I booked a flight last week but have not received my ticket confirmation. Please help.',
                'type' => 'booking',
                'is_read' => true,
                'read_at' => now()->subHours(5),
                'ip_address' => '192.168.1.101',
            ],
            [
                'name' => 'Ahmed Ali',
                'email' => 'ahmed.ali@email.com',
                'phone' => '+966553456789',
                'subject' => 'Visa Application Status',
                'message' => 'When can I expect my visa to be processed? I applied 5 days ago.',
                'type' => 'visa',
                'is_read' => true,
                'read_at' => now()->subDays(1),
                'ip_address' => '192.168.1.102',
            ],
            [
                'name' => 'Sarah Johnson',
                'email' => 'sarah.j@email.com',
                'subject' => 'Cargo Service Inquiry',
                'message' => 'I need to ship some documents to Dubai. What are your rates and how long does it take?',
                'type' => 'cargo',
                'is_read' => false,
                'ip_address' => '192.168.1.103',
            ],
            [
                'name' => 'Mohammed Khan',
                'email' => 'mohammed.k@email.com',
                'subject' => 'Feedback - Excellent Service',
                'message' => 'Just wanted to say thank you for the wonderful service. The Umrah experience you arranged was perfect!',
                'type' => 'feedback',
                'is_read' => true,
                'read_at' => now()->subHours(2),
                'ip_address' => '192.168.1.104',
            ],
        ];

        foreach ($messages as $messageData) {
            ContactMessage::updateOrCreate(
                [
                    'email' => $messageData['email'],
                    'subject' => $messageData['subject'],
                ],
                $messageData
            );
        }

        $this->command->info('ContactMessageSeeder: Created ' . count($messages) . ' sample contact messages!');
    }
}
