<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            ['name' => 'menu-product', 'category_name' => 'Product', 'method_name' => 'menu'],
            ['name' => 'list-product', 'category_name' => 'Product', 'method_name' => 'list'],
            ['name' => 'create-product', 'category_name' => 'Product', 'method_name' => 'create'],
            ['name' => 'show-product', 'category_name' => 'Product', 'method_name' => 'show'],
            ['name' => 'update-product', 'category_name' => 'Product', 'method_name' => 'update'],
            ['name' => 'delete-product', 'category_name' => 'Product', 'method_name' => 'delete'],

            ['name' => 'menu-review', 'category_name' => 'Review', 'method_name' => 'menu'],
            ['name' => 'list-review', 'category_name' => 'Review', 'method_name' => 'list'],
            ['name' => 'create-review', 'category_name' => 'Review', 'method_name' => 'create'],
            ['name' => 'show-review', 'category_name' => 'Review', 'method_name' => 'show'],
            ['name' => 'update-review', 'category_name' => 'Review', 'method_name' => 'update'],
            ['name' => 'delete-review', 'category_name' => 'Review', 'method_name' => 'delete'],
            ['name' => 'review-publish', 'category_name' => 'Review Publish', 'method_name' => 'other'],
        ];

        foreach ($permissions as $permission) {
            if (!Permission::whereName($permission)->first()) {
                Permission::create([
                    'name' => $permission['name'],
                    'category_name' =>  $permission['category_name'],
                    'method_name' => $permission['method_name'],
                    'guard_name' => 'api',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
            }
        }
    }
}
