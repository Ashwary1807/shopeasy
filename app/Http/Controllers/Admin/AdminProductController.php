<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminProductController extends Controller
{
    /**
     * Display a paginated list of products with filters, search, and inventory metrics.
     */
    public function index(Request $request): View
    {
        // 1. Metric counter summaries
        $metrics = [
            'total' => Product::count(),
            'active' => Product::where('status', 'active')->count(),
            'inactive' => Product::where('status', 'inactive')->count(),
            'out_of_stock' => Product::where('stock', '<=', 0)->count(),
            'low_stock' => Product::where('stock', '>', 0)->where('stock', '<=', 15)->count(),
        ];

        // 2. Base query
        $query = Product::query()->latest('id');

        // 3. Status / Inventory filter tabs
        $selectedStatus = strtolower(trim((string) $request->query('status', 'all')));
        if ($selectedStatus === 'active') {
            $query->where('status', 'active');
        } elseif ($selectedStatus === 'inactive') {
            $query->where('status', 'inactive');
        } elseif ($selectedStatus === 'out_of_stock') {
            $query->where('stock', '<=', 0);
        } elseif ($selectedStatus === 'low_stock') {
            $query->where('stock', '>', 0)->where('stock', '<=', 15);
        } else {
            $selectedStatus = 'all';
        }

        // 4. Category filter
        $selectedCategory = trim((string) $request->query('category', ''));
        if (!empty($selectedCategory)) {
            $query->where('category', $selectedCategory);
        }

        // 5. Keyword search filter
        $search = trim((string) $request->query('search', ''));
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%")
                    ->orWhere('category', 'LIKE', "%{$search}%");
            });
        }

        // 6. Paginate with 10 products per page and preserve query strings
        $products = $query->paginate(10)->withQueryString();

        // 7. Distinct categories for filter dropdown
        $categories = Product::distinct()->orderBy('category')->pluck('category');

        return view('admin.products.index', compact('products', 'metrics', 'selectedStatus', 'selectedCategory', 'search', 'categories'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create(): View
    {
        $categories = Product::distinct()->orderBy('category')->pluck('category');

        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created product in storage with validated input and image handling.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|min:2|max:255',
            'category' => 'required|string|max:100',
            'price' => 'required|numeric|min:0.01|max:999999.99',
            'original_price' => 'nullable|numeric|min:0.01|max:999999.99',
            'stock' => 'required|integer|min:0|max:999999',
            'status' => 'required|in:active,inactive',
            'badge' => 'nullable|string|max:50',
            'badge_color' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:3000',
            'image_url' => 'nullable|url|max:1000',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'name.required' => 'Product name is required.',
            'category.required' => 'Please select or enter a category.',
            'price.required' => 'Please specify a selling price.',
            'price.min' => 'Selling price must be at least $0.01.',
            'stock.required' => 'Stock quantity is required.',
            'stock.integer' => 'Stock quantity must be a whole number.',
            'stock.min' => 'Stock quantity cannot be negative.',
            'status.in' => 'Status must be either active or inactive.',
            'image_file.image' => 'The uploaded file must be a valid image.',
            'image_file.mimes' => 'Image must be of type: jpeg, png, jpg, or webp.',
            'image_file.max' => 'Image file size must not exceed 2MB.',
        ]);

        // Secure image handling
        $imagePath = $this->handleImageUpload($request);

        Product::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'original_price' => $validated['original_price'] ?? null,
            'category' => $validated['category'],
            'image' => $imagePath,
            'stock' => $validated['stock'],
            'status' => $validated['status'],
            'rating' => 5.0,
            'reviews_count' => 0,
            'badge' => $validated['badge'] ?? null,
            'badge_color' => $validated['badge_color'] ?? null,
        ]);

        return redirect()->route('admin.products.index')
            ->with('success', "Product \"{$validated['name']}\" created successfully.");
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(int $id): View
    {
        $product = Product::findOrFail($id);
        $categories = Product::distinct()->orderBy('category')->pluck('category');

        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product in storage with validated input.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|min:2|max:255',
            'category' => 'required|string|max:100',
            'price' => 'required|numeric|min:0.01|max:999999.99',
            'original_price' => 'nullable|numeric|min:0.01|max:999999.99',
            'stock' => 'required|integer|min:0|max:999999',
            'status' => 'required|in:active,inactive',
            'badge' => 'nullable|string|max:50',
            'badge_color' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:3000',
            'image_url' => 'nullable|url|max:1000',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'name.required' => 'Product name is required.',
            'category.required' => 'Please select or enter a category.',
            'price.required' => 'Please specify a selling price.',
            'price.min' => 'Selling price must be at least $0.01.',
            'stock.required' => 'Stock quantity is required.',
            'stock.integer' => 'Stock quantity must be a whole number.',
            'stock.min' => 'Stock quantity cannot be negative.',
            'status.in' => 'Status must be either active or inactive.',
            'image_file.image' => 'The uploaded file must be a valid image.',
            'image_file.mimes' => 'Image must be of type: jpeg, png, jpg, or webp.',
            'image_file.max' => 'Image file size must not exceed 2MB.',
        ]);

        // Secure image handling (preserves existing if unchanged)
        $newImagePath = $this->handleImageUpload($request, $product->image);

        $product->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'original_price' => $validated['original_price'] ?? null,
            'category' => $validated['category'],
            'image' => $newImagePath,
            'stock' => $validated['stock'],
            'status' => $validated['status'],
            'badge' => $validated['badge'] ?? null,
            'badge_color' => $validated['badge_color'] ?? null,
        ]);

        return redirect()->route('admin.products.index')
            ->with('success', "Product \"{$product->name}\" updated successfully.");
    }

    /**
     * Fast active / inactive status toggle.
     */
    public function updateStatus(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:active,inactive',
        ], [
            'status.required' => 'Please select a status.',
            'status.in' => 'Selected status must be either active or inactive.',
        ]);

        $product = Product::findOrFail($id);
        $newStatus = $validated['status'];

        if ($product->status === $newStatus) {
            return back()->with('info', "Product \"{$product->name}\" is already {$newStatus}.");
        }

        $product->status = $newStatus;
        $product->save();

        $actionWord = ($newStatus === 'active') ? 'activated and is now visible on storefront' : 'deactivated and hidden from storefront';

        return back()->with('success', "Product \"{$product->name}\" has been {$actionWord}.");
    }

    /**
     * Dedicated stock replenishment and inventory adjustment endpoint.
     */
    public function updateStock(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'stock' => 'required|integer|min:0|max:999999',
        ], [
            'stock.required' => 'Stock value is required.',
            'stock.integer' => 'Stock must be an integer.',
            'stock.min' => 'Stock cannot be negative.',
        ]);

        $product = Product::findOrFail($id);
        $oldStock = $product->stock;
        $newStock = (int) $validated['stock'];

        $product->stock = $newStock;
        $product->save();

        return back()->with('success', "Stock for \"{$product->name}\" updated from {$oldStock} to {$newStock}.");
    }

    /**
     * Helper to process local image uploads and external image URLs securely.
     */
    protected function handleImageUpload(Request $request, ?string $currentImage = null): string
    {
        // 1. If an image file was uploaded
        if ($request->hasFile('image_file') && $request->file('image_file')->isValid()) {
            $file = $request->file('image_file');
            $uploadDirectory = public_path('uploads/products');

            // Ensure destination directory exists safely
            if (!File::isDirectory($uploadDirectory)) {
                File::makeDirectory($uploadDirectory, 0755, true, true);
            }

            // Generate a collision-resistant, path-traversal-free filename
            $extension = strtolower($file->getClientOriginalExtension());
            if (!in_array($extension, ['jpeg', 'png', 'jpg', 'webp'], true)) {
                $extension = 'jpg';
            }
            $safeFilename = 'prod_' . date('Ymd_His') . '_' . Str::random(12) . '.' . $extension;

            // Move uploaded file to public/uploads/products
            $file->move($uploadDirectory, $safeFilename);

            return '/uploads/products/' . $safeFilename;
        }

        // 2. If an external or custom image URL was provided
        $imageUrl = trim((string) $request->input('image_url', ''));
        if (!empty($imageUrl)) {
            return $imageUrl;
        }

        // 3. Fallback to existing image if editing, or safe default placeholder
        if (!empty($currentImage)) {
            return $currentImage;
        }

        return 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=600&q=80';
    }
}
