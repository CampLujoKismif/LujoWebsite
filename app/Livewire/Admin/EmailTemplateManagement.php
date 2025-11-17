<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class EmailTemplateManagement extends Component
{
    public $templates = [];
    public $selectedTemplate = null;
    public $templateContent = '';
    public $templatePath = '';
    public $message = null;
    public $error = null;
    public $saving = false;
    public $showPreview = false;
    public $previewHtml = '';

    public function mount()
    {
        $this->loadTemplates();
    }

    public function loadTemplates()
    {
        $this->templates = [
            [
                'path' => 'emails/rentals/submission-admin-notification.blade.php',
                'name' => 'Rental Submission Notification (Admin)',
                'description' => 'Sent to admins when a new rental request is submitted',
                'category' => 'rental',
                'type' => 'admin_notification',
            ],
            [
                'path' => 'emails/rentals/admin-notification.blade.php',
                'name' => 'Rental Confirmation Notification (Admin)',
                'description' => 'Sent to admins when a rental is confirmed',
                'category' => 'rental',
                'type' => 'admin_notification',
            ],
            [
                'path' => 'emails/rentals/refund-admin-notification.blade.php',
                'name' => 'Rental Refund Notification (Admin)',
                'description' => 'Sent to admins when a refund is processed',
                'category' => 'rental',
                'type' => 'admin_notification',
            ],
            [
                'path' => 'emails/rentals/submission-confirmed.blade.php',
                'name' => 'Rental Submission Confirmation (Customer)',
                'description' => 'Sent to customers when rental reservation is submitted',
                'category' => 'rental',
                'type' => 'customer',
            ],
            [
                'path' => 'emails/rentals/confirmed.blade.php',
                'name' => 'Rental Confirmation (Customer)',
                'description' => 'Sent to customers when rental is confirmed',
                'category' => 'rental',
                'type' => 'customer',
            ],
            [
                'path' => 'emails/rentals/refunded.blade.php',
                'name' => 'Rental Refund (Customer)',
                'description' => 'Sent to customers when refund is processed',
                'category' => 'rental',
                'type' => 'customer',
            ],
            [
                'path' => 'emails/rentals/request-payment.blade.php',
                'name' => 'Rental Payment Request (Customer)',
                'description' => 'Sent to customers to request payment for their rental reservation',
                'category' => 'rental',
                'type' => 'customer',
            ],
            [
                'path' => 'emails/verify-email.blade.php',
                'name' => 'Email Verification',
                'description' => 'Sent for email address verification',
                'category' => 'auth',
                'type' => 'customer',
            ],
        ];
    }

    public function selectTemplate($path)
    {
        $this->selectedTemplate = $path;
        $this->templatePath = $path;
        $this->message = null;
        $this->error = null;

        $filePath = resource_path('views/' . $path);

        if (!File::exists($filePath)) {
            $this->error = 'Template not found';
            $this->templateContent = '';
            return;
        }

        try {
            $this->templateContent = File::get($filePath);
        } catch (\Exception $e) {
            $this->error = 'Failed to load template: ' . $e->getMessage();
            Log::error('Failed to read email template', [
                'path' => $path,
                'error' => $e->getMessage()
            ]);
            $this->templateContent = '';
        }
    }

    public function saveTemplate()
    {
        $this->validate([
            'templatePath' => 'required|string',
            'templateContent' => 'required|string',
        ]);

        $filePath = resource_path('views/' . $this->templatePath);

        if (!File::exists($filePath)) {
            $this->error = 'Template not found';
            return;
        }

        $this->saving = true;
        $this->message = null;
        $this->error = null;

        try {
            // Create backup
            $backupPath = $filePath . '.backup.' . date('YmdHis');
            File::copy($filePath, $backupPath);

            // Save the file
            File::put($filePath, $this->templateContent);

            Log::info('Email template updated', [
                'template' => $this->templatePath,
                'user' => auth()->user()->id,
            ]);

            $this->message = 'Template saved successfully!';
        } catch (\Exception $e) {
            $this->error = 'Failed to save template: ' . $e->getMessage();
            Log::error('Failed to save email template', [
                'template' => $this->templatePath,
                'error' => $e->getMessage(),
            ]);
        } finally {
            $this->saving = false;
        }
    }

    public function resetEditor()
    {
        $this->selectedTemplate = null;
        $this->templateContent = '';
        $this->templatePath = '';
        $this->message = null;
        $this->error = null;
        $this->showPreview = false;
        $this->previewHtml = '';
    }

    public function previewTemplate()
    {
        if (empty($this->templateContent)) {
            $this->error = 'No content to preview';
            return;
        }
        
        // Just show the HTML content as-is
        // Note: This won't render Blade variables, but shows the HTML structure
        $this->previewHtml = $this->templateContent;
        $this->showPreview = true;
    }

    public function closePreview()
    {
        $this->showPreview = false;
        $this->previewHtml = '';
    }

    public function getTemplateName()
    {
        $template = collect($this->templates)->firstWhere('path', $this->selectedTemplate);
        return $template['name'] ?? 'Unknown';
    }

    public function getTemplateDescription()
    {
        $template = collect($this->templates)->firstWhere('path', $this->selectedTemplate);
        return $template['description'] ?? '';
    }

    public function render()
    {
        return view('livewire.admin.email-template-management')
            ->layout('components.layouts.app');
    }
}
