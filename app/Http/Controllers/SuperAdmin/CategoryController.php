<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('id', 'asc')->paginate(10);
        return view('superadmin.category', compact('categories'));
    }

    public function toggleHome($id)
    {
        $cat = Category::findOrFail($id);

        if (!$cat->is_home) {
            $count = Category::where('is_home', 1)->count();
            if ($count >= 12) {
                return back()->with('error', 'You can select only 12 categories for homepage.');
            }
        }

        $cat->is_home = !$cat->is_home;
        $cat->save();

        return back()->with('success', 'Homepage categories updated!');
    }

    // Main category image compress
    private function compressAndStoreMainImage(
        UploadedFile $file,
        string $folder = 'categories',
        int $maxKb = 500,
        int $maxWidth = 1200
    ): string {
        $manager = new ImageManager(new Driver());
        $image   = $manager->read($file->getPathname());

        $image->scaleDown(width: $maxWidth);

        $filename = uniqid('cat_') . '.jpg';
        $path     = $folder . '/' . $filename;

        $quality = 85;
        do {
            $encoded = $image->toJpeg($quality);
            $quality -= 5;
        } while (strlen((string)$encoded) > ($maxKb * 1024) && $quality > 30);

        Storage::disk('public')->put($path, (string)$encoded);

        return $path;
    }

    // Unique slug generate
    private function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($name);

        if (empty($baseSlug)) {
            $baseSlug = 'category';
        }

        $slug = $baseSlug;
        $counter = 1;

        while (
            Category::where('slug', $slug)
            ->when($ignoreId, function ($query) use ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            })
            ->exists()
        ) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'title'         => ['nullable', 'string', 'max:255'],
            'description'   => ['nullable', 'string'],
            'keyword'       => ['nullable', 'string'],
            'categoryimage' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'image'         => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'is_active'     => ['nullable', 'in:0,1'],
        ]);

        $category = new Category();
        $category->name        = $data['name'];
        $category->slug        = $this->generateUniqueSlug($data['name']);
        $category->title       = $data['title'] ?? null;
        $category->description = $data['description'] ?? null;
        $category->keyword     = $data['keyword'] ?? null;
        $category->is_active   = (bool)($data['is_active'] ?? 0);

        // icon image
        if ($request->hasFile('categoryimage')) {
            $category->categoryimage = $request->file('categoryimage')->store('category-icon', 'public');
        }

        // main image
        if ($request->hasFile('image')) {
            $category->image = $this->compressAndStoreMainImage(
                $request->file('image'),
                'categories',
                500,
                1200
            );
        }

        $category->save();

        return back()->with('success', 'Category added successfully!');
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $data = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'title'         => ['nullable', 'string', 'max:255'],
            'description'   => ['nullable', 'string'],
            'keyword'       => ['nullable', 'string'],
            'categoryimage' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'image'         => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'is_active'     => ['nullable', 'in:0,1'],
        ]);

        $category->name        = $data['name'];
        $category->slug        = $this->generateUniqueSlug($data['name'], $category->id);
        $category->title       = $data['title'] ?? null;
        $category->description = $data['description'] ?? null;
        $category->keyword     = $data['keyword'] ?? null;
        $category->is_active   = (bool)($data['is_active'] ?? 0);

        // icon image update
        if ($request->hasFile('categoryimage')) {
            if ($category->categoryimage && Storage::disk('public')->exists($category->categoryimage)) {
                Storage::disk('public')->delete($category->categoryimage);
            }
            $category->categoryimage = $request->file('categoryimage')->store('category-icon', 'public');
        }

        // main image update
        if ($request->hasFile('image')) {
            if ($category->image && Storage::disk('public')->exists($category->image)) {
                Storage::disk('public')->delete($category->image);
            }

            $category->image = $this->compressAndStoreMainImage(
                $request->file('image'),
                'categories',
                500,
                1200
            );
        }

        $category->save();

        return back()->with('success', 'Category updated successfully!');
    }

    public function destroy(Category $category)
    {
        if ($category->image && Storage::disk('public')->exists($category->image)) {
            Storage::disk('public')->delete($category->image);
        }

        if ($category->categoryimage && Storage::disk('public')->exists($category->categoryimage)) {
            Storage::disk('public')->delete($category->categoryimage);
        }

        $category->delete();

        return back()->with('success', 'Category deleted successfully!');
    }

    public function toggleStatus(Category $category)
    {
        $category->is_active = !$category->is_active;
        $category->save();

        return back()->with('success', 'Status updated!');
    }
}
