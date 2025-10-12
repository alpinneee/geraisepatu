<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Mail\ContactFormMail;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{
    /**
     * Display the home page.
     */
    public function index()
    {
        // Get active banners for carousel
        $banners = Banner::active()->ordered()->get();
        
        // Get featured products
        $featuredProducts = Product::active()
            ->inStock()
            ->with('category')
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();
        
        // Get products on sale
        $saleProducts = Product::active()
            ->inStock()
            ->onSale()
            ->with('category')
            ->orderBy('discount_price', 'asc')
            ->take(8)
            ->get();
        
        // Get product categories
        $categories = Category::active()
            ->ordered()
            ->take(6)
            ->get();
        
        return view('customer.home', compact('banners', 'featuredProducts', 'saleProducts', 'categories'));
    }
    
    /**
     * Display the about page.
     */
    public function about()
    {
        return view('customer.about');
    }
    
    /**
     * Display the contact page.
     */
    public function contact()
    {
        return view('customer.contact');
    }
    
    /**
     * Process contact form submission.
     */
    public function submitContact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);
        
        $contactData = [
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
            'submitted_at' => now(),
        ];
        
        try {
            // Simpan ke database
            Contact::create([
                'name' => $contactData['name'],
                'email' => $contactData['email'],
                'subject' => $contactData['subject'],
                'message' => $contactData['message'],
            ]);
            
            // Kirim email ke admin
            $adminEmail = config('mail.admin_email', 'admin@geraisepatu.xyz');
            Mail::to($adminEmail)->send(new ContactFormMail($contactData));
            
            return back()->with('success', 'Terima kasih atas pesan Anda. Tim kami akan segera menghubungi Anda!');
        } catch (\Exception $e) {
            \Log::error('Failed to send contact form email: ' . $e->getMessage());
            return back()->with('error', 'Maaf, terjadi kesalahan saat mengirim pesan. Silakan coba lagi.');
        }
    }
    
    /**
     * Display the FAQ page.
     */
    public function faq()
    {
        return view('customer.faq');
    }
    

}
