<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FAQ;
use App\Models\FAQItem;
use App\Models\BusinessListing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FAQController extends Controller
{
    public function index()
    {
        $admin = auth()->user();

        $listingIds = BusinessListing::where('user_id', $admin->id)->pluck('id');

        $faqs = FAQ::with('listing')
            ->whereIn('listing_id', $listingIds)
            ->latest()
            ->get();

        return view('admin.faq.index', compact('faqs'));
    }

    public function create()
    {
        $admin = auth()->user();

        $listings = BusinessListing::select('id', 'business_name')
            ->where('user_id', $admin->id)
            ->orderBy('business_name')
            ->get();

        return view('admin.faq.create', compact('listings'));
    }

    public function store(Request $request)
    {
        $admin = auth()->user();
        $allowedListingIds = BusinessListing::where('user_id', $admin->id)->pluck('id')->toArray();

        $validated = $request->validate([
            'listing_id'   => ['required', 'integer', 'in:' . implode(',', $allowedListingIds)],
            'listing_name' => ['required', 'string', 'max:255'],

            'faq_items'            => ['required', 'array', 'min:1'],
            'faq_items.*.question' => ['required', 'string', 'max:255'],
            'faq_items.*.answer'   => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($validated) {
            $faq = FAQ::create([
                'listing_id'   => $validated['listing_id'],
                'listing_name' => $validated['listing_name'],
            ]);

            $rows = [];
            foreach ($validated['faq_items'] as $i => $item) {
                $rows[] = [
                    'faq_id'      => $faq->id,
                    'question'    => $item['question'],
                    'answer'      => $item['answer'] ?? null,
                    'sort_order'  => $i,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ];
            }
            FAQItem::insert($rows);
        });

        return redirect()->route('admin.faq.index')->with('success', 'FAQ saved successfully.');
    }

    public function edit(FAQ $faq)
    {
        $admin = auth()->user();

        $owns = BusinessListing::where('id', $faq->listing_id)
            ->where('user_id', $admin->id)
            ->exists();
        abort_if(!$owns, 403);

        $listings = BusinessListing::select('id', 'business_name')
            ->where('user_id', $admin->id)
            ->orderBy('business_name')
            ->get();

        $faq->load('items');

        return view('admin.faq.edit', compact('faq', 'listings'));
    }

    public function update(Request $request, FAQ $faq)
    {
        $admin = auth()->user();

        $allowedListingIds = BusinessListing::where('user_id', $admin->id)->pluck('id')->toArray();

        abort_if(!in_array($faq->listing_id, $allowedListingIds), 403);

        $validated = $request->validate([
            'listing_id'   => ['required', 'integer', 'in:' . implode(',', $allowedListingIds)],
            'listing_name' => ['required', 'string', 'max:255'],

            'faq_items'            => ['required', 'array', 'min:1'],
            'faq_items.*.question' => ['required', 'string', 'max:255'],
            'faq_items.*.answer'   => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($validated, $faq) {
            $faq->update([
                'listing_id'   => $validated['listing_id'],
                'listing_name' => $validated['listing_name'],
            ]);

            $faq->items()->delete();

            $rows = [];
            foreach ($validated['faq_items'] as $i => $item) {
                $rows[] = [
                    'faq_id'      => $faq->id,
                    'question'    => $item['question'],
                    'answer'      => $item['answer'] ?? null,
                    'sort_order'  => $i,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ];
            }
            FAQItem::insert($rows);
        });

        return redirect()->route('admin.faq.index')->with('success', 'FAQ updated successfully.');
    }

    public function destroy(FAQ $faq)
    {
        $admin = auth()->user();

        $owns = BusinessListing::where('id', $faq->listing_id)
            ->where('user_id', $admin->id)
            ->exists();
        abort_if(!$owns, 403);

        $faq->delete();

        return redirect()->route('admin.faq.index')->with('success', 'FAQ deleted successfully.');
    }
}
