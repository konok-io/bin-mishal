<?php

namespace App\Http\Controllers\Admin\Cargo;

use App\Http\Controllers\Controller;
use App\Models\Cargo\CargoCoupon;
use Illuminate\Http\Request;

class CargoCouponController extends Controller
{
    public function index()
    {
        $coupons = CargoCoupon::orderBy('created_at', 'desc')->get();
        return view('admin.cargo.coupons.index', compact('coupons'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:cargo_coupons,code',
            'name' => 'required|string|max:255',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'valid_from' => 'required|date',
            'valid_until' => 'required|date|after:valid_from',
            'is_active' => 'boolean',
        ]);

        CargoCoupon::create($validated);

        return redirect()->back()->with('success', 'Coupon created successfully');
    }

    public function update(Request $request, CargoCoupon $coupon)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:cargo_coupons,code,' . $coupon->id,
            'name' => 'required|string|max:255',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'valid_from' => 'required|date',
            'valid_until' => 'required|date|after:valid_from',
            'is_active' => 'boolean',
        ]);

        $coupon->update($validated);

        return redirect()->back()->with('success', 'Coupon updated successfully');
    }

    public function destroy(CargoCoupon $coupon)
    {
        $coupon->delete();
        return redirect()->back()->with('success', 'Coupon deleted successfully');
    }
}
