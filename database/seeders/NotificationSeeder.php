<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\NotificationTemplate;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            [
                'name' => 'Booking Created',
                'event' => NotificationTemplate::EVENT_BOOKING_CREATED,
                'type' => 'email',
                'channels' => ['email', 'sms'],
                'subject' => 'Booking Confirmation - {{booking_number}}',
                'subject_bn' => 'বুকিং নিশ্চিতকরণ - {{booking_number}}',
                'subject_ar' => 'تأكيد الحجز - {{booking_number}}',
                'body' => "Dear {{customer_name}},\n\nYour booking has been received and is pending confirmation.\n\nBooking Details:\n- Booking Number: {{booking_number}}\n- Service: {{service_type}}\n- Date: {{booking_date}}\n- Amount: {{total_amount}}\n\nWe will notify you once your booking is confirmed.\n\nBest regards,\nBin Mishal Travels",
                'variables' => [
                    'customer_name' => 'Customer full name',
                    'booking_number' => 'Unique booking reference',
                    'service_type' => 'Type of service',
                    'booking_date' => 'Booking date',
                    'total_amount' => 'Total amount',
                ],
            ],
            [
                'name' => 'Booking Confirmed',
                'event' => NotificationTemplate::EVENT_BOOKING_CONFIRMED,
                'type' => 'email',
                'channels' => ['email', 'sms', 'whatsapp'],
                'subject' => 'Booking Confirmed! - {{booking_number}}',
                'subject_bn' => 'বুকিং নিশ্চিত হয়েছে! - {{booking_number}}',
                'subject_ar' => 'تم تأكيد الحجز! - {{booking_number}}',
                'body' => "Dear {{customer_name}},\n\nGreat news! Your booking has been confirmed.\n\nBooking Details:\n- Booking Number: {{booking_number}}\n- Service: {{service_type}}\n- Date: {{booking_date}}\n- Status: CONFIRMED\n\nPlease proceed with payment to complete your booking.\n\nBest regards,\nBin Mishal Travels",
                'variables' => [
                    'customer_name' => 'Customer full name',
                    'booking_number' => 'Unique booking reference',
                    'service_type' => 'Type of service',
                    'booking_date' => 'Booking date',
                ],
            ],
            [
                'name' => 'Cargo Booked',
                'event' => NotificationTemplate::EVENT_CARGO_BOOKED,
                'type' => 'email',
                'channels' => ['email', 'sms'],
                'subject' => 'Cargo Shipment Booked - {{tracking_number}}',
                'subject_bn' => 'কার্গো শিপমেন্ট বুক করা হয়েছে - {{tracking_number}}',
                'subject_ar' => 'تم حجز شحنة البضائع - {{tracking_number}}',
                'body' => "Dear {{sender_name}},\n\nYour cargo shipment has been booked.\n\nShipment Details:\n- Tracking Number: {{tracking_number}}\n- Origin: {{origin_city}}\n- Destination: {{destination_city}}\n- Weight: {{weight}} kg\n- Estimated Cost: {{total_amount}}\n\nWe will update you as your shipment progresses.\n\nBest regards,\nBin Mishal Travels",
                'variables' => [
                    'sender_name' => 'Sender name',
                    'tracking_number' => 'Cargo tracking number',
                    'origin_city' => 'Origin city',
                    'destination_city' => 'Destination city',
                    'weight' => 'Package weight',
                    'total_amount' => 'Estimated cost',
                ],
            ],
            [
                'name' => 'Cargo In Transit',
                'event' => NotificationTemplate::EVENT_CARGO_IN_TRANSIT,
                'type' => 'email',
                'channels' => ['email', 'sms', 'whatsapp'],
                'subject' => 'Your Shipment Is On The Way - {{tracking_number}}',
                'subject_bn' => 'আপনার শিপমেন্ট পথে আছে - {{tracking_number}}',
                'subject_ar' => 'شحنتك في الطريق - {{tracking_number}}',
                'body' => "Dear {{receiver_name}},\n\nGreat news! Your shipment is now in transit.\n\nTracking Number: {{tracking_number}}\nCurrent Status: In Transit\nLocation: {{current_location}}\n\nExpected delivery: {{estimated_delivery}}\n\nTrack your shipment in real-time on our website.\n\nBest regards,\nBin Mishal Travels",
                'variables' => [
                    'receiver_name' => 'Receiver name',
                    'tracking_number' => 'Cargo tracking number',
                    'current_location' => 'Current location',
                    'estimated_delivery' => 'Expected delivery date',
                ],
            ],
            [
                'name' => 'Cargo Delivered',
                'event' => NotificationTemplate::EVENT_CARGO_DELIVERED,
                'type' => 'email',
                'channels' => ['email', 'sms', 'whatsapp'],
                'subject' => 'Shipment Delivered! - {{tracking_number}}',
                'subject_bn' => 'শিপমেন্ট ডেলিভারি হয়েছে! - {{tracking_number}}',
                'subject_ar' => 'تم تسليم الشحنة! - {{tracking_number}}',
                'body' => "Dear {{receiver_name}},\n\nYour shipment has been delivered successfully!\n\nTracking Number: {{tracking_number}}\nDelivered At: {{delivered_at}}\nDelivered To: {{delivery_address}}\n\nThank you for choosing Bin Mishal Travels.\n\nBest regards,\nBin Mishal Travels",
                'variables' => [
                    'receiver_name' => 'Receiver name',
                    'tracking_number' => 'Cargo tracking number',
                    'delivered_at' => 'Delivery timestamp',
                    'delivery_address' => 'Delivery address',
                ],
            ],
            [
                'name' => 'Investor Application Submitted',
                'event' => NotificationTemplate::EVENT_INVESTOR_APPLICATION,
                'type' => 'email',
                'channels' => ['email'],
                'subject' => 'Application Received - {{application_number}}',
                'subject_bn' => 'আবেদন গ্রহণ করা হয়েছে - {{application_number}}',
                'subject_ar' => 'تم استلام الطلب - {{application_number}}',
                'body' => "Dear {{full_name}},\n\nThank you for your investor application.\n\nApplication Number: {{application_number}}\nService: {{service_type}}\nSubmitted At: {{submitted_at}}\n\nOur team will review your application and get back to you within 3-5 business days.\n\nBest regards,\nBin Mishal Travels",
                'variables' => [
                    'full_name' => 'Applicant name',
                    'application_number' => 'Application reference',
                    'service_type' => 'Service type',
                    'submitted_at' => 'Submission time',
                ],
            ],
            [
                'name' => 'Investor Application Approved',
                'event' => NotificationTemplate::EVENT_INVESTOR_APPROVED,
                'type' => 'email',
                'channels' => ['email', 'sms', 'whatsapp'],
                'subject' => 'Congratulations! Application Approved - {{application_number}}',
                'subject_bn' => 'অভিনন্দন! আবেদন অনুমোদিত - {{application_number}}',
                'subject_ar' => 'تهانينا! تم الموافقة على الطلب - {{application_number}}',
                'body' => "Dear {{full_name}},\n\nCongratulations! Your investor application has been approved.\n\nApplication Number: {{application_number}}\nService: {{service_type}}\nApproved At: {{approved_at}}\n\nOur team will contact you shortly with next steps and documentation.\n\nWelcome aboard!\nBin Mishal Travels",
                'variables' => [
                    'full_name' => 'Applicant name',
                    'application_number' => 'Application reference',
                    'service_type' => 'Service type',
                    'approved_at' => 'Approval time',
                ],
            ],
            [
                'name' => 'Contact Form Submission',
                'event' => NotificationTemplate::EVENT_CONTACT_FORM,
                'type' => 'email',
                'channels' => ['email'],
                'subject' => 'New Contact Form Submission',
                'subject_bn' => 'নতুন যোগাযোগ ফর্ম জমা',
                'subject_ar' => 'نموذج اتصال جديد',
                'body' => "New contact form submission:\n\nName: {{name}}\nEmail: {{email}}\nPhone: {{phone}}\nSubject: {{subject}}\n\nMessage:\n{{message}}\n\nSubmitted at: {{submitted_at}}",
                'variables' => [
                    'name' => 'Visitor name',
                    'email' => 'Visitor email',
                    'phone' => 'Visitor phone',
                    'subject' => 'Message subject',
                    'message' => 'Message content',
                    'submitted_at' => 'Submission time',
                ],
            ],
        ];

        foreach ($templates as $template) {
            NotificationTemplate::updateOrCreate(
                ['event' => $template['event']],
                $template
            );
        }

        $this->command->info('Notification templates seeded successfully.');
    }
}
