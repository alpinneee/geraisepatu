<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Contact::query();

        // Filter berdasarkan status
        if ($request->has('status')) {
            if ($request->status === 'unread') {
                $query->unread();
            } elseif ($request->status === 'read') {
                $query->read();
            }
        }

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        $contacts = $query->orderBy('created_at', 'desc')->paginate(15);
        $unreadCount = Contact::unread()->count();

        return view('admin.contacts.index', compact('contacts', 'unreadCount'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Contact $contact)
    {
        // Mark as read when viewed
        if (!$contact->is_read) {
            $contact->markAsRead();
        }

        return view('admin.contacts.show', compact('contact'));
    }

    /**
     * Mark contact as replied.
     */
    public function markAsReplied(Contact $contact)
    {
        $contact->markAsReplied();

        return back()->with('success', 'Contact marked as replied successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contact $contact)
    {
        $contact->delete();

        return redirect()->route('admin.contacts.index')
            ->with('success', 'Contact deleted successfully.');
    }

    /**
     * Mark multiple contacts as read.
     */
    public function markAsRead(Request $request)
    {
        $contactIds = $request->input('contact_ids', []);
        
        if (!empty($contactIds)) {
            Contact::whereIn('id', $contactIds)->update(['is_read' => true]);
            return back()->with('success', 'Selected contacts marked as read.');
        }

        return back()->with('error', 'No contacts selected.');
    }

    /**
     * Delete multiple contacts.
     */
    public function bulkDelete(Request $request)
    {
        $contactIds = $request->input('contact_ids', []);
        
        if (!empty($contactIds)) {
            Contact::whereIn('id', $contactIds)->delete();
            return back()->with('success', 'Selected contacts deleted successfully.');
        }

        return back()->with('error', 'No contacts selected.');
    }
}