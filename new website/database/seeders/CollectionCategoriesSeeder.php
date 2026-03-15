<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Webkul\Category\Repositories\CategoryRepository;

/**
 * Seeds collection categories so homepage links (Kids Clothing, Hats, etc.) open category pages.
 * Run: php artisan db:seed --class=CollectionCategoriesSeeder
 */
class CollectionCategoriesSeeder extends Seeder
{
    protected static array $collections = [
        ['slug' => 'kids-clothing', 'name' => 'Kids Clothing', 'description' => 'Curated kids clothing collection.'],
        ['slug' => 'hats', 'name' => 'Hats', 'description' => 'Hats for every style.'],
        ['slug' => 'sweaters', 'name' => 'Sweaters', 'description' => 'Cozy sweaters collection.'],
        ['slug' => 'costumes', 'name' => 'Costumes', 'description' => 'Costumes and dress-up.'],
        ['slug' => 'jackets', 'name' => 'Outerwear', 'description' => 'Jackets and outerwear.'],
        ['slug' => 't-shirts', 'name' => 'T-Shirts', 'description' => 'T-shirts collection.'],
        ['slug' => 'pants', 'name' => 'Pants', 'description' => 'Pants and bottoms.'],
    ];

    public function run(): void
    {
        $categoryRepository = app(CategoryRepository::class);
        $now = Carbon::now();
        $locales = DB::table('locales')->pluck('code')->toArray();
        if (empty($locales)) {
            $locales = [config('app.locale', 'en')];
        }

        $root = DB::table('categories')->where('id', 1)->first();
        if (! $root) {
            $this->command?->warn('Root category (id=1) not found. Run bagisto:install first.');
            return;
        }

        $rootRgt = (int) $root->_rgt;
        $nextId = (int) DB::table('categories')->max('id') + 1;
        $toInsert = [];
        $itemsToInsert = [];

        foreach (self::$collections as $item) {
            if ($categoryRepository->findBySlug($item['slug'])) {
                $this->command?->info("Category {$item['slug']} already exists, skipping.");
                continue;
            }
            $i = count($toInsert);
            $lft = $rootRgt + $i * 2;
            $rgt = $rootRgt + $i * 2 + 1;
            $toInsert[] = [
                'id' => $nextId + $i,
                'position' => 1,
                'logo_path' => null,
                'status' => 1,
                'display_mode' => 'products_and_description',
                '_lft' => $lft,
                '_rgt' => $rgt,
                'parent_id' => 1,
                'additional' => null,
                'banner_path' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ];
            $itemsToInsert[] = $item;
        }

        if (empty($toInsert)) {
            return;
        }

        $newRootRgt = $rootRgt + count($toInsert) * 2;
        DB::table('categories')->where('id', 1)->update(['_rgt' => $newRootRgt, 'updated_at' => $now]);
        DB::table('categories')->insert($toInsert);

        foreach ($toInsert as $i => $row) {
            $item = $itemsToInsert[$i];
            foreach ($locales as $locale) {
                DB::table('category_translations')->insert([
                    'category_id' => $row['id'],
                    'name' => $item['name'],
                    'slug' => $item['slug'],
                    'url_path' => $item['slug'],
                    'description' => $item['description'] ?? '',
                    'meta_title' => $item['name'],
                    'meta_description' => '',
                    'meta_keywords' => '',
                    'locale_id' => DB::table('locales')->where('code', $locale)->value('id'),
                    'locale' => $locale,
                ]);
            }
            $this->command?->info("Created category: {$item['name']} ({$item['slug']}).");
        }
    }
}
