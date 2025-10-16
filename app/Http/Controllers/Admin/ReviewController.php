<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with(['user', 'product']);
        
        if ($request->has('status')) {
            if ($request->status === 'approved') {
                $query->where('is_approved', true);
            } elseif ($request->status === 'pending') {
                $query->where('is_approved', false);
            }
        }
        
        $reviews = $query->latest()->paginate(20);
        
        return view('admin.reviews.index', compact('reviews'));
    }
    
    public function approve(Review $review)
    {
        $review->update(['is_approved' => true]);
        
        return back()->with('success', 'Review berhasil disetujui.');
    }
    
    public function reject(Review $review)
    {
        $review->update(['is_approved' => false]);
        
        return back()->with('success', 'Review berhasil ditolak.');
    }
    
    public function destroy(Review $review)
    {
        $review->delete();
        
        return back()->with('success', 'Review berhasil dihapus.');
    }
}