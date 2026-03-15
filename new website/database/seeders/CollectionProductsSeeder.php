<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Webkul\Category\Repositories\CategoryRepository;
use Webkul\Product\Models\Product;
use Webkul\Product\Repositories\ProductAttributeValueRepository;
use Webkul\Product\Repositories\ProductImageRepository;
use Webkul\Product\Repositories\ProductInventoryRepository;
use Webkul\Product\Repositories\ProductRepository;

class CollectionProductsSeeder extends Seeder
{
    /**
     * Create/refresh simple products from collection images.
     */
    public function run(): void
    {
        $collections = [
            [
                'title' => 'Clothes',
                'folder' => 'Clothes',
                'slugs' => ['kids-clothing'],
            ],
            [
                'title' => 'Costumes',
                'folder' => 'Costumes',
                'slugs' => ['costumes'],
            ],
            [
                'title' => 'Hats',
                'folder' => 'Hats',
                'slugs' => ['hats'],
            ],
            [
                'title' => 'Outwear',
                'folder' => 'Outwear',
                'slugs' => ['outwear', 'jackets'],
            ],
            [
                'title' => 'Pants',
                'folder' => 'Pants',
                'slugs' => ['pants'],
            ],
            [
                'title' => 'Sweaters',
                'folder' => 'Sweaters',
                'slugs' => ['sweaters'],
            ],
        ];

        $categoryRepository = app(CategoryRepository::class);
        $productRepository = app(ProductRepository::class);
        $productAttributeValueRepository = app(ProductAttributeValueRepository::class);
        $productInventoryRepository = app(ProductInventoryRepository::class);
        $productImageRepository = app(ProductImageRepository::class);

        $attributeFamilyId = DB::table('attribute_families')
            ->where('code', 'default')
            ->value('id')
            ?? DB::table('attribute_families')->value('id');

        $defaultChannel = core()->getDefaultChannel();
        $defaultLocale = core()->getDefaultLocaleCodeFromDefaultChannel();

        $channelId = $defaultChannel?->id;
        $inventorySourceId = DB::table('inventory_sources')->value('id');

        if (! $attributeFamilyId || ! $channelId || ! $inventorySourceId) {
            $this->command?->warn('Missing attribute family, channel, or inventory source. Seed aborted.');

            return;
        }

        foreach ($collections as $collection) {
            $category = null;

            foreach ($collection['slugs'] as $slug) {
                $category = $categoryRepository->findBySlug($slug);

                if ($category) {
                    break;
                }
            }

            if (! $category) {
                $this->command?->warn("Category not found for {$collection['title']}, skipping.");

                continue;
            }

            $pattern = public_path("images/collections/{$collection['folder']}/*/*");

            $images = collect(glob($pattern) ?: [])
                ->filter(function (string $filePath) {
                    return in_array(strtolower(pathinfo($filePath, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'webp']);
                })
                ->sort()
                ->values();

            if ($images->isEmpty()) {
                $this->command?->warn("No images found for {$collection['title']}, skipping.");

                continue;
            }

            foreach ($images as $index => $imagePath) {
                $productNumber = $index + 1;
                $titleSlug = Str::slug($collection['title']);
                $colorSlug = Str::slug(basename(dirname($imagePath)));
                $imageSlug = Str::slug(pathinfo($imagePath, PATHINFO_FILENAME));

                $sku = Str::limit("collection-{$titleSlug}-{$colorSlug}-{$imageSlug}", 90, '');

                $product = Product::query()->where('sku', $sku)->first();

                if (! $product) {
                    $product = $productRepository->create([
                        'sku' => $sku,
                        'type' => 'simple',
                        'attribute_family_id' => $attributeFamilyId,
                    ]);
                }

                $uploadedImage = new UploadedFile(
                    $imagePath,
                    basename($imagePath),
                    mime_content_type($imagePath) ?: null,
                    null,
                    true
                );

                $name = "{$collection['title']} {$productNumber}";

                $urlKey = Str::limit("{$titleSlug}-{$colorSlug}-{$imageSlug}", 180, '');

                $price = 699 + (abs(crc32($sku)) % 1401);

                $product->update([
                    'sku' => $sku,
                    'type' => 'simple',
                    'attribute_family_id' => $attributeFamilyId,
                ]);

                $product->channels()->sync([$channelId]);
                $product->categories()->sync([$category->id]);

                $attributeData = [
                    'name' => $name,
                    'url_key' => $urlKey,
                    'price' => $price,
                    'status' => 1,
                    'visible_individually' => 1,
                    'new' => 1,
                    'featured' => 1,
                    'guest_checkout' => 1,
                    'weight' => 1,
                    'short_description' => "{$name} from the {$collection['title']} section.",
                    'description' => "{$name} from the {$collection['title']} section. Ready to add to cart.",
                    'manage_stock' => 1,
                    'channel' => $defaultChannel->code,
                    'locale' => $defaultLocale,
                ];

                $product->loadMissing('attribute_family', 'attribute_values');

                $productAttributeValueRepository->saveValues(
                    $attributeData,
                    $product,
                    $product->attribute_family->custom_attributes
                );

                $productInventoryRepository->saveInventories([
                    'inventories' => [$inventorySourceId => 100],
                ], $product);

                $productImageRepository->upload([
                    'images' => [
                        'files' => [$uploadedImage],
                    ],
                ], $product, 'images');

                $this->command?->info("Upserted: {$name} ({$sku})");
            }
        }
    }
}
