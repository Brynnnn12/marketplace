<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Seller;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Dapatkan sellers yang ada
        $sellers = Seller::all();

        if ($sellers->isEmpty()) {
            $this->command->warn('No sellers found. Please run SellerSeeder first.');
            return;
        }

        $products = [
            // Products untuk John's Electronics Store
            [
                'seller_id' => $sellers->where('store_name', 'John\'s Electronics Store')->first()?->id,
                'name' => 'Ultimate Web Development Course',
                'description' => 'Complete web development course covering HTML, CSS, JavaScript, PHP, and Laravel. Includes 50+ hours of video content, source code, and projects.',
                'price' => 299000,
                'image' => 'product-images/web-dev-course.jpg',
                'file_path' => 'product-files/web-development-course.zip',
            ],
            [
                'seller_id' => $sellers->where('store_name', 'John\'s Electronics Store')->first()?->id,
                'name' => 'Mobile App Development Bundle',
                'description' => 'Learn to build mobile apps with React Native and Flutter. Complete with project files and deployment guides.',
                'price' => 450000,
                'image' => 'product-images/mobile-app-bundle.jpg',
                'file_path' => 'product-files/mobile-app-bundle.zip',
            ],

            // Products untuk Sarah's Fashion Boutique
            [
                'seller_id' => $sellers->where('store_name', 'Sarah\'s Fashion Boutique')->first()?->id,
                'name' => 'Fashion Design Templates Pack',
                'description' => 'Professional fashion design templates for Photoshop and Illustrator. Includes 100+ templates for clothing designs.',
                'price' => 150000,
                'image' => 'product-images/fashion-templates.jpg',
                'file_path' => 'product-files/fashion-design-templates.zip',
            ],
            [
                'seller_id' => $sellers->where('store_name', 'Sarah\'s Fashion Boutique')->first()?->id,
                'name' => 'Style Guide E-book',
                'description' => 'Complete style guide for modern women. Digital e-book with 200+ pages of fashion tips and outfit ideas.',
                'price' => 75000,
                'image' => 'product-images/style-guide-ebook.jpg',
                'file_path' => 'product-files/style-guide-ebook.pdf',
            ],

            // Products untuk Mike's Sports Equipment
            [
                'seller_id' => $sellers->where('store_name', 'Mike\'s Sports Equipment')->first()?->id,
                'name' => 'Fitness Training Program',
                'description' => 'Complete 12-week fitness training program with workout videos, nutrition guide, and tracking sheets.',
                'price' => 200000,
                'image' => 'product-images/fitness-program.jpg',
                'file_path' => 'product-files/fitness-training-program.zip',
            ],
            [
                'seller_id' => $sellers->where('store_name', 'Mike\'s Sports Equipment')->first()?->id,
                'name' => 'Sports Photography Presets',
                'description' => 'Professional Lightroom presets for sports photography. Enhance your action shots with these premium presets.',
                'price' => 120000,
                'image' => 'product-images/sports-presets.jpg',
                'file_path' => 'product-files/sports-photography-presets.zip',
            ],
        ];

        foreach ($products as $productData) {
            if ($productData['seller_id']) {
                Product::firstOrCreate(
                    [
                        'name' => $productData['name'],
                        'seller_id' => $productData['seller_id']
                    ],
                    $productData
                );
            }
        }
    }
}
