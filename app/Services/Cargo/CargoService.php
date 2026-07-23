<?php

namespace App\Services\Cargo;

use App\Models\Cargo\Cargo;
use App\Models\Cargo\CargoCoupon;
use App\Models\Cargo\CargoPricing;
use App\Models\Cargo\CargoTracking;
use App\Models\Cargo\CargoPackage;
use Illuminate\Support\Facades\DB;

class CargoService
{
    /**
     * Generate unique tracking number
     */
    public static function generateTrackingNumber(): string
    {
        $prefix = 'CG';
        $year = date('Y');
        $lastCargo = Cargo::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();
        
        $lastNumber = $lastCargo ? intval(substr($lastCargo->tracking_number, -6)) : 0;
        $newNumber = str_pad($lastNumber + 1, 6, '0', STR_PAD_LEFT);
        
        return "{$prefix}-{$year}-{$newNumber}";
    }

    /**
     * Calculate shipping price
     */
    public static function calculatePrice($originCityId, $destinationCityId, $cargoTypeId, $weight, $packageId = null)
    {
        $basePrice = 0;
        $vatPercentage = 15;

        // Check package-based pricing first
        if ($packageId) {
            $package = CargoPackage::find($packageId);
            if ($package) {
                $basePrice = $package->base_price;
                $vatPercentage = $package->vat_percentage;
            }
        }

        // Check weight-based pricing
        $pricing = CargoPricing::where('is_active', true)
            ->where(function ($query) use ($originCityId, $destinationCityId, $cargoTypeId) {
                $query->where('origin_city_id', $originCityId)
                    ->where('destination_city_id', $destinationCityId);
                
                if ($cargoTypeId) {
                    $query->orWhere('cargo_type_id', $cargoTypeId);
                }
            })
            ->where(function ($query) use ($weight) {
                $query->where('pricing_type', '!=', 'weight')
                    ->orWhere(function ($q) use ($weight) {
                        $q->where('pricing_type', 'weight')
                          ->where('min_weight', '<=', $weight)
                          ->where('max_weight', '>=', $weight);
                    });
            })
            ->first();

        if ($pricing && !$packageId) {
            $basePrice = $pricing->calculatePrice($weight);
            $vatPercentage = $pricing->vat_percentage;
        }

        $vatAmount = ($basePrice * $vatPercentage) / 100;
        $total = $basePrice + $vatAmount;

        return [
            'base_price' => round($basePrice, 2),
            'vat_amount' => round($vatAmount, 2),
            'vat_percentage' => $vatPercentage,
            'total' => round($total, 2),
        ];
    }

    /**
     * Apply coupon discount
     */
    public static function applyCoupon($couponCode, $totalAmount)
    {
        $coupon = CargoCoupon::where('code', $couponCode)->first();
        
        if (!$coupon) {
            return ['success' => false, 'message' => 'Invalid coupon code'];
        }

        if (!$coupon->isValid()) {
            return ['success' => false, 'message' => 'This coupon is not valid or has expired'];
        }

        $discount = $coupon->calculateDiscount($totalAmount);
        
        return [
            'success' => true,
            'discount' => round($discount, 2),
            'coupon_id' => $coupon->id,
            'coupon_code' => $coupon->code,
        ];
    }

    /**
     * Create cargo with tracking
     */
    public static function createCargo(array $data): Cargo
    {
        return DB::transaction(function () use ($data) {
            $cargo = Cargo::create([
                'tracking_number' => self::generateTrackingNumber(),
                'customer_id' => $data['customer_id'] ?? null,
                'sender_name' => $data['sender_name'],
                'sender_phone' => $data['sender_phone'],
                'sender_email' => $data['sender_email'] ?? null,
                'sender_address' => $data['sender_address'],
                'sender_city' => $data['sender_city'],
                'receiver_name' => $data['receiver_name'],
                'receiver_phone' => $data['receiver_phone'],
                'receiver_email' => $data['receiver_email'] ?? null,
                'receiver_address' => $data['receiver_address'],
                'receiver_city' => $data['receiver_city'],
                'receiver_zone_id' => $data['receiver_zone_id'] ?? null,
                'cargo_type_id' => $data['cargo_type_id'] ?? null,
                'cargo_package_id' => $data['cargo_package_id'] ?? null,
                'cargo_description' => $data['cargo_description'] ?? null,
                'quantity' => $data['quantity'] ?? 1,
                'weight' => $data['weight'] ?? 0,
                'length' => $data['length'] ?? 0,
                'width' => $data['width'] ?? 0,
                'height' => $data['height'] ?? 0,
                'declared_value' => $data['declared_value'] ?? 0,
                'shipping_cost' => $data['shipping_cost'],
                'vat_amount' => $data['vat_amount'] ?? 0,
                'discount_amount' => $data['discount_amount'] ?? 0,
                'coupon_id' => $data['coupon_id'] ?? null,
                'total_amount' => $data['total_amount'],
                'pickup_date' => $data['pickup_date'] ?? null,
                'pickup_time' => $data['pickup_time'] ?? null,
                'estimated_delivery' => $data['estimated_delivery'] ?? null,
                'delivery_days' => $data['delivery_days'] ?? 3,
                'special_instructions' => $data['special_instructions'] ?? null,
                'status' => Cargo::STATUS_PENDING,
                'payment_status' => Cargo::PAYMENT_UNPAID,
            ]);

            // Create initial tracking
            self::addTracking($cargo, Cargo::STATUS_PENDING, 'Cargo booking created');

            // Increment coupon usage if applied
            if ($cargo->coupon_id) {
                $coupon = CargoCoupon::find($cargo->coupon_id);
                if ($coupon) {
                    $coupon->incrementUsage();
                }
            }

            return $cargo;
        });
    }

    /**
     * Add tracking entry
     */
    public static function addTracking(Cargo $cargo, string $status, string $description, ?string $location = null)
    {
        return CargoTracking::create([
            'cargo_id' => $cargo->id,
            'status' => $status,
            'status_bn' => self::getStatusBn($status),
            'status_ar' => self::getStatusAr($status),
            'description' => $description,
            'description_bn' => $description,
            'description_ar' => $description,
            'location' => $location,
            'timestamp' => now(),
            'notify_customer' => true,
        ]);
    }

    /**
     * Update cargo status
     */
    public static function updateStatus(Cargo $cargo, string $status, ?string $description = null, ?string $location = null)
    {
        $cargo->update(['status' => $status]);
        
        self::addTracking(
            $cargo, 
            $status, 
            $description ?? "Status updated to {$status}",
            $location
        );

        return $cargo;
    }

    /**
     * Get status in Bengali
     */
    private static function getStatusBn(string $status): string
    {
        $statuses = [
            'pending' => 'অপেক্ষমান',
            'confirmed' => 'নিশ্চিত',
            'collected' => 'সংগ্রহ করা হয়েছে',
            'warehouse' => 'গুদামে',
            'in_transit' => 'ট্রানজিটে',
            'customs' => 'শুল্ক',
            'bd_hub' => 'বাংলাদেশ হাব',
            'out_for_delivery' => 'ডেলিভারির জন্য বের হয়েছে',
            'delivered' => 'ডেলিভারি সম্পন্ন',
            'cancelled' => 'বাতিল',
            'returned' => 'ফেরত',
        ];
        return $statuses[$status] ?? $status;
    }

    /**
     * Get status in Arabic
     */
    private static function getStatusAr(string $status): string
    {
        $statuses = [
            'pending' => 'قيد الانتظار',
            'confirmed' => 'تأكيد',
            'collected' => 'تم الجمع',
            'warehouse' => 'المستودع',
            'in_transit' => 'في العبور',
            'customs' => 'الجمارك',
            'bd_hub' => 'مركز بنجلاديش',
            'out_for_delivery' => 'خارج للتسليم',
            'delivered' => 'تم التسليم',
            'cancelled' => 'ملغى',
            'returned' => 'مرتجع',
        ];
        return $statuses[$status] ?? $status;
    }
}
