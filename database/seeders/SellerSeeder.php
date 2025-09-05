<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Seller;
use Illuminate\Support\Facades\Hash;

class SellerSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Buat beberapa user untuk dijadikan seller
        $users = [
            [
                'name' => 'John Store Owner',
                'email' => 'john@store.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Sarah Shop Manager',
                'email' => 'sarah@shop.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Mike Marketplace',
                'email' => 'mike@marketplace.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
        ];

        foreach ($users as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                $userData
            );

            // Assign seller role if exists
            if ($user->hasRole('Seller') === false && \Spatie\Permission\Models\Role::where('name', 'Seller')->exists()) {
                $user->assignRole('Seller');
            }
        }

        // Buat data seller
        $sellers = [
            [
                'user_id' => User::where('email', 'john@store.com')->first()->id,
                'store_name' => 'John\'s Electronics Store',
                'store_description' => 'Quality electronics and gadgets at affordable prices. We specialize in smartphones, laptops, and accessories.',
                'status' => 'approved',
            ],
            [
                'user_id' => User::where('email', 'sarah@shop.com')->first()->id,
                'store_name' => 'Sarah\'s Fashion Boutique',
                'store_description' => 'Trendy fashion for modern women. From casual wear to elegant dresses.',
                'status' => 'pending',
            ],
            [
                'user_id' => User::where('email', 'mike@marketplace.com')->first()->id,
                'store_name' => 'Mike\'s Sports Equipment',
                'store_description' => 'Professional sports equipment and outdoor gear for athletes and enthusiasts.',
                'status' => 'rejected',
            ],
        ];

        foreach ($sellers as $sellerData) {
            Seller::firstOrCreate(
                ['user_id' => $sellerData['user_id']],
                $sellerData
            );
        }
    }
}
