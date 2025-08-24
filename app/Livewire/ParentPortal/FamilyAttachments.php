<?php

namespace App\Livewire\ParentPortal;

use App\Models\Document;
use App\Models\Family;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;

class FamilyAttachments extends Component
{
    use WithFileUploads, WithPagination;

    public $family;
    public $showUploadModal = false;
    public $uploadedFile;
    public $label = '';
    public $description = '';

    protected $rules = [
        'uploadedFile' => 'required|file|max:10240', // 10MB max
        'label' => 'required|string|max:255',
        'description' => 'nullable|string|max:500',
    ];

    public function mount(Family $family)
    {
        $this->family = $family;
    }

    public function render()
    {
        $documents = $this->family->documents()
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.parent-portal.family-attachments', compact('documents'));
    }

    public function showUpload()
    {
        $this->resetForm();
        $this->showUploadModal = true;
    }

    public function upload()
    {
        $this->validate();

        $path = $this->uploadedFile->store('family-documents', 'public');
        
        Document::create([
            'family_id' => $this->family->id,
            'path' => $path,
            'label' => $this->label,
            'description' => $this->description,
            'file_size' => $this->uploadedFile->getSize(),
            'mime_type' => $this->uploadedFile->getMimeType(),
            'uploaded_by_user_id' => auth()->id(),
        ]);

        $this->showUploadModal = false;
        $this->resetForm();
        $this->dispatch('document-uploaded');
    }

    public function delete(Document $document)
    {
        // Check if user has permission to delete this document
        if ($document->uploaded_by_user_id !== auth()->id() && !auth()->user()->hasRole('system-admin')) {
            $this->dispatch('error', 'You do not have permission to delete this document.');
            return;
        }

        // Delete the file from storage
        if (Storage::disk('public')->exists($document->path)) {
            Storage::disk('public')->delete($document->path);
        }

        $document->delete();
        $this->dispatch('document-deleted');
    }

    public function download(Document $document)
    {
        if (!Storage::disk('public')->exists($document->path)) {
            $this->dispatch('error', 'File not found.');
            return;
        }

        return Storage::disk('public')->download($document->path, $document->label . '.' . $document->extension);
    }

    private function resetForm()
    {
        $this->uploadedFile = null;
        $this->label = '';
        $this->description = '';
    }

    public function closeModal()
    {
        $this->showUploadModal = false;
        $this->resetForm();
    }
}
