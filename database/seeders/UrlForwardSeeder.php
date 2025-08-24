<?php

namespace Database\Seeders;

use App\Models\UrlForward;
use App\Models\User;
use Illuminate\Database\Seeder;

class UrlForwardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first system admin user or create one
        $admin = User::whereHas('roles', function($query) {
            $query->where('name', 'system-admin');
        })->first();

        if (!$admin) {
            $admin = User::first();
        }

        if (!$admin) {
            return; // No users exist yet
        }

        $urlForwards = [
            [
                'internal_url' => 'go/example',
                'external_url' => 'https://example.com',
                'title' => 'Example Website',
                'description' => 'A simple example URL forward',
                'is_active' => true,
            ],
            [
                'internal_url' => 'go/google',
                'external_url' => 'https://google.com',
                'title' => 'Google',
                'description' => 'Redirect to Google search',
                'is_active' => true,
            ],
            [
                'internal_url' => 'go/github',
                'external_url' => 'https://github.com',
                'title' => 'GitHub',
                'description' => 'Redirect to GitHub',
                'is_active' => true,
            ],
        ];

        foreach ($urlForwards as $urlForward) {
            UrlForward::create([
                ...$urlForward,
                'created_by' => $admin->id,
            ]);
        }
    }
}
