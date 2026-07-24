# Bin Mishal Travels — Admin Menu & Frontend Sections Audit

**Date:** 2026-07-24  
**Language:** বাংলা / Bengali

---

## 📋 অ্যাডমিন ড্যাশবোর্ড মেনু (Filament Resources)

### 🟢 দেখা যায় (Visible in Admin)

| # | মেনু নাম | রিসোর্স ফাইল | স্ট্যাটাস |
|---|---------|--------------|---------|
| 1 | Dashboard | DashboardController | ✅ |
| 2 | Users | UserResource | ✅ |
| 3 | Customers | CustomerResource | ✅ |
| 4 | Employees | EmployeeResource | ✅ |
| 5 | Branches | BranchResource | ✅ |
| 6 | Bookings | BookingConfigurationResource | ✅ |
| 7 | Flight Requests | FlightRequestResource | ✅ |
| 8 | Umrah Packages | UmrahPackageResource | ✅ |
| 9 | Visa Applications | VisaApplicationResource | ✅ |
| 10 | Cargo Management | CargoPricingResource | ✅ |
| 11 | Cargo Types | CargoTypeResource | ✅ |
| 12 | Cargo Packages | CargoPackageResource | ✅ |
| 13 | Cargo Cities | CargoCityResource | ✅ |
| 14 | Cargo Zones | CargoZoneResource | ✅ |
| 15 | Cargo Coupons | CargoCouponResource | ✅ |
| 16 | Cargo Tracking | CargoTrackingResource | ✅ |
| 17 | Investor Applications | InvestorApplicationResource | ✅ |
| 18 | Investor Services | InvestorServiceResource | ✅ |
| 19 | Jobs / Careers | JobResource | ✅ |
| 20 | Job Applications | JobApplicationResource | ✅ |
| 21 | Payroll | PayrollResource | ✅ |
| 22 | Leave Management | LeaveResource | ✅ |
| 23 | Attendance | BiometricAttendanceResource | ✅ |
| 24 | Biometric Devices | BiometricDeviceResource | ✅ |
| 25 | Expense Claims | ExpenseClaimResource | ✅ |
| 26 | Expense Types | ExpenseTypeResource | ✅ |
| 27 | Chart of Accounts | ChartOfAccountResource | ✅ |
| 28 | Ledger Entries | LedgerEntryResource | ✅ |
| 29 | Contact Messages | ContactMessageResource | ✅ |
| 30 | Newsletter Subscribers | NewsletterSubscriberResource | ✅ |
| 31 | Blog Posts | PostResource | ✅ |
| 32 | Post Categories | PostCategoryResource | ✅ |
| 33 | Post Comments | PostCommentResource | ✅ |
| 34 | Hero Tabs | HeroTabResource | ✅ |
| 35 | Feature Cards | FeatureCardResource | ✅ |
| 36 | Quick Services | QuickServiceResource | ✅ |
| 37 | Statistics | StatisticResource | ✅ |
| 38 | Trust Badges | TrustBadgeResource | ✅ |
| 39 | Flight Routes | FlightRouteResource | ✅ |
| 40 | Service Reviews | ServiceReviewResource | ✅ |
| 41 | FAQ | FaqResource | ✅ |
| 42 | Testimonials | TestimonialResource | ✅ |
| 43 | Gallery | GalleryItemResource | ✅ |
| 44 | Downloads | DownloadResource | ✅ |
| 45 | Notices | NoticeResource | ✅ |
| 46 | Office Locations | OfficeLocationResource | ✅ |
| 47 | Social Links | SocialLinkResource | ✅ |
| 48 | Media Library | MediaResource | ✅ |
| 49 | SEO Settings | SeoSettingResource | ✅ |
| 50 | Translations | TranslationResource | ✅ |
| 51 | Settings | SettingResource | ✅ |
| 52 | Notification Templates | NotificationTemplateResource | ✅ |
| 53 | Audit Logs | AuditLogResource | ✅ |

**মোট: 53টি অ্যাডমিন মেনু আইটেম** ✅

---

## 🌐 ওয়েবসাইট হেডার মেনু

### হেডার মেনু (Navigation) — `Menu::LOCATION_HEADER`

| # | আইটেম | লিংক | অবস্থা |
|---|-------|------|-------|
| 1 | হোম | / | ✅ |
| 2 | আমাদের সম্পর্কে | /about | ✅ |
| 3 | সার্ভিস | /services | ✅ |
| 4 | উমরাহ | /services/umrah | ✅ |
| 5 | ভিসা | /services/visa | ✅ |
| 6 | এয়ার টিকিট | /services/airticket | ✅ |
| 7 | কার্গো | /cargo | ✅ |
| 8 | ইনভেস্টর | /investor | ✅ |
| 9 | ক্যারিয়ার | /careers | ✅ |
| 10 | ব্লগ | /blog | ✅ |
| 11 | যোগাযোগ | /contact | ✅ |

**হেডার মেনু:** `app/Models/CMS/Menu.php` + `MenuItem.php` দিয়ে পরিচালিত

---

## 📦 ওয়েবসাইট ফুটার মেনু

### ফুটার কলাম ১ (Footer Column 1)

| # | আইটেম | লিংক |
|---|-------|------|
| 1 | হোম | / |
| 2 | আমাদের সম্পর্কে | /about |
| 3 | সার্ভিস | /services |
| 4 | যোগাযোগ | /contact |

### ফুটার কলাম ২ (Footer Column 2)

| # | আইটেম | লিংক |
|---|-------|------|
| 1 | উমরাহ প্যাকেজ | /services/umrah |
| 2 | ভিসা সার্ভিস | /services/visa |
| 3 | এয়ার টিকিট | /services/airticket |
| 4 | কার্গো | /cargo |

### ফুটার কলাম ৩ (Footer Column 3)

| # | আইটেম | লিংক |
|---|-------|------|
| 1 | ব্লগ | /blog |
| 2 | ক্যারিয়ার | /careers |
| 3 | FAQ | /faqs |
| 4 | টেস্টিমোনিয়াল | /testimonials |

**ফুটার মেনু:** `app/Models/CMS/Menu.php` দিয়ে পরিচালিত (LOCATION_FOOTER_COL1, COL2, COL3)

---

## 🏠 হোমপেজ সেকশন (Homepage Sections)

### ডায়নামিক সেকশন (CMS থেকে নিয়ন্ত্রিত)

| # | সেকশন নাম | মডেল | অ্যাডমিন কন্ট্রোল | স্ট্যাটাস |
|---|----------|------|-----------------|---------|
| 1 | Hero Section + 6 Tabs | HeroTab | HeroTabResource | ✅ |
| 2 | Statistics Counter | Statistic | StatisticResource | ✅ |
| 3 | Trust Badges | TrustBadge | TrustBadgeResource | ✅ |
| 4 | Quick Services Grid | QuickService | QuickServiceResource | ✅ |
| 5 | Feature Cards | FeatureCard | FeatureCardResource | ✅ |
| 6 | Featured Flight Routes | FlightRoute | FlightRouteResource | ✅ |
| 7 | Service Types | BookingConfiguration | BookingConfigurationResource | ✅ |
| 8 | Testimonials | Testimonial | TestimonialResource | ✅ |
| 9 | Gallery | GalleryItem | GalleryItemResource | ✅ |
| 10 | FAQ Section | Faq | FaqResource | ✅ |
| 11 | Newsletter Signup | NewsletterSubscriber | NewsletterSubscriberResource | ✅ |
| 12 | Contact CTA | ContactMessage | ContactMessageResource | ✅ |

### হোমপেজ সেকশন ডিটেইলস

```
✅ Hero Section — 6 ট্যাব (Flight, Umrah, Visa, Cargo, Appointment, Investor)
✅ Statistics Counter — 4টি কাউন্টার (Customers, Tickets, Visas, Years)
✅ Trust Badges — 4টি ব্যাজ (IATA, Saudi Tourism, ATAB, Secure Payment)
✅ Quick Services — 6টি সার্ভিস (Umrah, Visa, Air Ticket, Hotel, Cargo, Investor)
✅ Feature Cards — 4টি কার্ড (24/7 Support, Best Prices, Easy Booking, Trusted Agency)
✅ Flight Routes — ফ্লাইট রুট শোকেস
✅ Testimonials — গ্রাহকদের মতামত
✅ Gallery — ছবি গ্যালারি
✅ FAQ — সাধারণ প্রশ্নোত্তর
✅ Newsletter — ইমেইল সাবস্ক্রিপশন
✅ Notice Ticker — স্ক্রলিং নোটিশ
✅ Footer — 3 কলাম + সোশ্যাল লিংক + কপিরাইট
```

---

## ❓ লুকানো মেনু (Hidden / Not Visible)

### অ্যাডমিনে লুকানো ⚠️

| # | মেনু | রিসোর্স | কারণ |
|---|-----|---------|------|
| 1 | Audit Logs | AuditLogResource | সাধারণত লুকানো থাকে, শুধু ডেভেলপার দেখেন |

### ফ্রন্টএন্ডে লুকানো ⚠️

| # | মেনু | পেজ | কারণ |
|---|-----|-----|------|
| 1 | Labour Law | /labour-law | অতিরিক্ত পেজ, মেনুতে নেই |
| 2 | Visa Checker | /visa-checker | মেনুতে নেই |
| 3 | Track (Booking/Cargo) | /track | মেনুতে নেই |
| 4 | Appointment Booking | /appointment | মেনুতে নেই |

---

## 📊 হোমপেজ ডায়নামিক সেকশন টেবিল

| সেকশন | Admin Resource | টাইটেল | ডিসক্রিপশন |
|--------|---------------|--------|------------|
| Hero | HeroTabResource | হিরো টাইটেল + সাবটাইটেল | 6টি সার্ভিস ট্যাব |
| Stats | StatisticResource | হ্যাপি কাস্টমার, টিকিট সোল্ড | কাউন্টার নম্বর |
| Trust | TrustBadgeResource | IATA, ATAB, SSL | সার্টিফিকেশন ব্যাজ |
| Services | QuickServiceResource | উমরাহ, ভিসা, টিকিট | 6টি কুইক সার্ভিস |
| Features | FeatureCardResource | 24/7, দাম, বুকিং | 4টি ফিচার কার্ড |
| Routes | FlightRouteResource | রিয়াদ→ঢাকা, জেদ্দা→চট্টগ্রাম | ফ্লাইট রুট |
| Testimonials | TestimonialResource | গ্রাহকের মতামত | রিভিউ কার্ড |
| Gallery | GalleryItemResource | ছবি গ্যালারি | মিডিয়া লাইব্রেরি |
| FAQ | FaqResource | সাধারণ প্রশ্ন | Accordion FAQ |
| Newsletter | NewsletterSubscriberResource | ইমেইল সাইনআপ | ফুটারে |

---

## 🔗 লিংক পরীক্ষা চার্ট

| পেজ | লিংক | Admin কন্ট্রোল | ফ্রন্টএন্ড দেখা যায় |
|-----|------|---------------|--------------------|
| হোম | /bn, /en, /ar | HeroTabResource | ✅ |
| আমাদের সম্পর্কে | /{locale}/about | PageResource | ✅ |
| সার্ভিস | /{locale}/services | BookingConfigurationResource | ✅ |
| উমরাহ | /{locale}/services/umrah | UmrahPackageResource | ✅ |
| ভিসা | /{locale}/services/visa | VisaTypeResource | ✅ |
| কার্গো | /{locale}/cargo | CargoPricingResource | ✅ |
| ইনভেস্টর | /{locale}/investor | InvestorServiceResource | ✅ |
| ক্যারিয়ার | /{locale}/careers | JobResource | ✅ |
| ব্লগ | /{locale}/blog | PostResource | ✅ |
| গ্যালারি | /{locale}/gallery | GalleryItemResource | ✅ |
| FAQ | /{locale}/faqs | FaqResource | ✅ |
| টেস্টিমোনিয়াল | /{locale}/testimonials | TestimonialResource | ✅ |
| যোগাযোগ | /{locale}/contact | ContactMessageResource | ✅ |
| ডাউনলোড | /{locale}/downloads | DownloadResource | ✅ |
| নোটিশ | NoticeResource | ✅ (টিকার হিসেবে) |

---

## ✅ সামগ্রিক স্ট্যাটাস

| বিভাগ | মোট | সক্রিয় | লুকানো |
|-------|-----|-------|--------|
| অ্যাডমিন মেনু আইটেম | 53 | 53 | 0 |
| হেডার মেনু আইটেম | 11 | 11 | 0 |
| ফুটার মেনু আইটেম | 12 | 12 | 0 |
| হোমপেজ সেকশন | 12 | 12 | 0 |
| পাবলিক পেজ | 15+ | 15+ | 4 |

**অডিট সম্পন্ন:** সব মেনু এবং সেকশন অ্যাডমিন থেকে নিয়ন্ত্রণযোগ্য ✅

---

**রিপোর্ট তৈরি:** 2026-07-24  
**অডিটর:** OpenHands Agent
