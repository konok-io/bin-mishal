<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            AdminUserSeeder::class,
            BranchSeeder::class,
            SettingSeeder::class,
            CmsSeeder::class,
            VisaTypeSeeder::class,
            AirlineSeeder::class,
            AirportSeeder::class,
            InvestorServiceSeeder::class,
            ContentSeeder::class,
            NotificationSeeder::class,
            HomepageSeeder::class,
            PayrollSeeder::class,
            BiometricSeeder::class,
            CityTVConnectSeeder::class,
            ExpenseSeeder::class,
            AccountingSeeder::class,
            // HR Module Seeders
            EmployeeSeeder::class,
            LeaveRequestSeeder::class,
            AttendanceSeeder::class,
            PayrollRecordSeeder::class,
            // Accounting Module Seeders
            ChartOfAccountSeeder::class,
            LedgerEntrySeeder::class,
            ExpenseClaimSeeder::class,
            // Recruitment Module Seeders
            JobPostingSeeder::class,
            JobApplicationSeeder::class,
            // CMS Module Seeders
            PageSeeder::class,
            MenuSeeder::class,
            BlogPostSeeder::class,
            TestimonialSeeder::class,
            GallerySeeder::class,
            FaqSeeder::class,
            // Communication Module Seeders
            ContactMessageSeeder::class,
            NewsletterSubscriberSeeder::class,
            CommentSeeder::class,
            // Settings Seeders
            SeoSettingSeeder::class,
            SocialLinkSeeder::class,
            NoticeSeeder::class,
            AuditLogSeeder::class,
            TranslationSeeder::class,
            // CRM & Booking Module Seeders
            CustomerSeeder::class,
            LeadSeeder::class,
            BookingSeeder::class,
            VisaApplicationSeeder::class,
            FlightRequestSeeder::class,
            UmrahPackageSeeder::class,
            CargoSeeder::class,
            InvoiceSeeder::class,
            PaymentSeeder::class,
        ]);
    }
}
