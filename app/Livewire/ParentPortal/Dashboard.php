<?php

namespace App\Livewire\ParentPortal;

use App\Models\CampInstance;
use App\Models\Enrollment;
use App\Models\Family;
use App\Models\Camper;
use App\Models\CamperInformationSnapshot;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Arr;

class Dashboard extends Component
{
    use WithFileUploads;

    public $stats = [];
    
    // Family editing
    public $showFamilyEditModal = false;
    public $familyName;
    public $phone;
    public $address;
    public $city;
    public $state;
    public $zipCode;
    public $emergencyContactName;
    public $emergencyContactPhone;
    public $emergencyContactRelationship;
    
    // Camper editing
    public $showCamperModal = false;
    public $editingCamperId = null;
    public $camperFirstName;
    public $camperLastName;
    public $camperDateOfBirth;
    public $camperBiologicalGender;
    public $camperGrade;
    public $camperTShirtSize;
    public $camperSchool;
    public $camperPhone;
    public $camperEmail;
    public $camperAllergies;
    public $camperMedicalConditions;
    public $camperMedications;
    public $camperPhoto;
    public $camperPhotoPreview;

    public function mount()
    {
        $this->loadStats();
        $this->loadFamilyData();
    }

    public function loadStats()
    {
        $user = auth()->user();
        
        // Get user's families and campers
        $families = $user->families()->with('campers')->get();
        $campers = collect();
        foreach ($families as $family) {
            $campers = $campers->concat($family->campers);
        }
        
        // Get active enrollments
        $activeEnrollments = Enrollment::whereIn('camper_id', $campers->pluck('id'))
            ->whereIn('status', ['pending', 'confirmed', 'waitlisted'])
            ->with(['camper', 'campInstance.camp'])
            ->get();
        
        // Calculate stats
        $this->stats = [
            'active_campers' => $campers->count(),
            'confirmed_enrollments' => $activeEnrollments->where('status', 'confirmed')->count(),
        ];
    }

    public function loadFamilyData()
    {
        $user = auth()->user();
        $family = $user->defaultFamily();
        
        $this->familyName = $family->name;
        $this->phone = $family->phone;
        $this->address = $family->address;
        $this->city = $family->city;
        $this->state = $family->state;
        $this->zipCode = $family->zip_code;
        $this->emergencyContactName = $family->emergency_contact_name;
        $this->emergencyContactPhone = $family->emergency_contact_phone;
        $this->emergencyContactRelationship = $family->emergency_contact_relationship;
    }

    public function getRecentActivityProperty()
    {
        $user = auth()->user();
        $families = $user->families()->with('campers')->get();
        $camperIds = collect();
        foreach ($families as $family) {
            $camperIds = $camperIds->concat($family->campers->pluck('id'));
        }

        // Get recent enrollments as activity
        $recentEnrollments = Enrollment::whereIn('camper_id', $camperIds)
            ->with(['camper', 'campInstance.camp'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return $recentEnrollments->map(function ($enrollment) {
            return (object) [
                'description' => "Enrollment for {$enrollment->camper->full_name} in {$enrollment->campInstance->camp->display_name}",
                'created_at' => $enrollment->created_at
            ];
        });
    }

    public function getUpcomingSessionsProperty()
    {
        $user = auth()->user();
        $families = $user->families()->with('campers')->get();
        $camperIds = collect();
        foreach ($families as $family) {
            $camperIds = $camperIds->concat($family->campers->pluck('id'));
        }

        // Get all active sessions where user has confirmed enrollments
        $activeSessions = CampInstance::where('is_active', true)
            ->whereHas('enrollments', function ($query) use ($camperIds) {
                $query->whereIn('camper_id', $camperIds)
                      ->where('status', 'confirmed');
            })
            ->with('camp')
            ->orderBy('start_date')
            ->limit(3)
            ->get();

        return $activeSessions;
    }

    public function openFamilyEditModal()
    {
        $this->loadFamilyData();
        $this->showFamilyEditModal = true;
    }

    public function closeFamilyEditModal()
    {
        $this->showFamilyEditModal = false;
        $this->resetValidation();
    }

    public function saveFamily()
    {
        $this->validate([
            'familyName' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'zipCode' => 'nullable|string|max:10',
            'emergencyContactName' => 'nullable|string|max:255',
            'emergencyContactPhone' => 'nullable|string|max:20',
            'emergencyContactRelationship' => 'nullable|string|max:100',
        ]);

        $user = auth()->user();
        $family = $user->defaultFamily();

        $family->update([
            'name' => $this->familyName,
            'phone' => $this->phone,
            'address' => $this->address,
            'city' => $this->city,
            'state' => $this->state,
            'zip_code' => $this->zipCode,
            'emergency_contact_name' => $this->emergencyContactName,
            'emergency_contact_phone' => $this->emergencyContactPhone,
            'emergency_contact_relationship' => $this->emergencyContactRelationship,
        ]);

        $this->closeFamilyEditModal();
        $this->dispatch('family-updated');
        session()->flash('message', 'Family information updated successfully.');
    }

    public function openCamperModal($camperId = null)
    {
        $this->editingCamperId = $camperId;
        $this->camperPhotoPreview = null;
        
        if ($camperId) {
            $user = auth()->user();
            $family = $user->defaultFamily();
            $camper = Camper::where('id', $camperId)
                ->where('family_id', $family->id)
                ->firstOrFail();
            
            $year = $this->defaultYear();
            $this->camperFirstName = $camper->first_name;
            $this->camperLastName = $camper->last_name;
            $this->camperDateOfBirth = $camper->date_of_birth ? $camper->date_of_birth->format('Y-m-d') : '';
            $this->camperBiologicalGender = $camper->biological_gender;
            $this->camperGrade = $camper->gradeForYear($year) ?? '';
            $this->camperTShirtSize = $camper->tShirtSizeForYear($year) ?? '';
            $this->camperSchool = $camper->school;
            $this->camperPhone = $camper->phone_number;
            $this->camperEmail = $camper->email;
            $this->camperAllergies = $camper->allergies;
            $this->camperMedicalConditions = $camper->medical_conditions;
            $this->camperMedications = $camper->medications;
            $this->camperPhotoPreview = $camper->photo_url;
        } else {
            $this->resetCamperFields();
        }
        
        $this->showCamperModal = true;
    }

    public function closeCamperModal()
    {
        $this->showCamperModal = false;
        $this->editingCamperId = null;
        $this->resetCamperFields();
        $this->resetValidation();
    }

    public function resetCamperFields()
    {
        $this->camperFirstName = '';
        $this->camperLastName = '';
        $this->camperDateOfBirth = '';
        $this->camperBiologicalGender = '';
        $this->camperGrade = '';
        $this->camperTShirtSize = '';
        $this->camperSchool = '';
        $this->camperPhone = '';
        $this->camperEmail = '';
        $this->camperAllergies = '';
        $this->camperMedicalConditions = '';
        $this->camperMedications = '';
        $this->camperPhoto = null;
        $this->camperPhotoPreview = null;
    }

    public function updatedCamperPhoto()
    {
        $this->validate([
            'camperPhoto' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);
        
        if ($this->camperPhoto) {
            $this->camperPhotoPreview = $this->camperPhoto->temporaryUrl();
        }
    }

    public function saveCamper()
    {
        $this->validate([
            'camperFirstName' => 'required|string|max:255',
            'camperLastName' => 'required|string|max:255',
            'camperDateOfBirth' => 'required|date|before:today',
            'camperBiologicalGender' => 'nullable|string|in:Male,Female',
            'camperGrade' => 'required|integer|min:1|max:12',
            'camperTShirtSize' => 'nullable|string|max:50',
            'camperSchool' => 'nullable|string|max:255',
            'camperPhone' => 'nullable|string|max:20',
            'camperEmail' => 'nullable|email|max:255',
            'camperAllergies' => 'nullable|string',
            'camperMedicalConditions' => 'nullable|string',
            'camperMedications' => 'nullable|string',
            'camperPhoto' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $user = auth()->user();
        $family = $user->defaultFamily();

        $camperAttributes = [
            'first_name' => $this->camperFirstName,
            'last_name' => $this->camperLastName,
            'date_of_birth' => $this->camperDateOfBirth,
            'biological_gender' => $this->camperBiologicalGender,
            'school' => $this->camperSchool,
            'phone_number' => $this->camperPhone,
            'email' => $this->camperEmail,
            'allergies' => $this->camperAllergies,
            'medical_conditions' => $this->camperMedicalConditions,
            'medications' => $this->camperMedications,
        ];

        // Handle photo upload
        if ($this->camperPhoto) {
            $photoPath = $this->camperPhoto->store('camper-photos', 'public');
            $camperAttributes['photo_path'] = $photoPath;
        }

        if ($this->editingCamperId) {
            $camper = Camper::where('id', $this->editingCamperId)
                ->where('family_id', $family->id)
                ->firstOrFail();
            
            // Delete old photo if new one is uploaded
            if ($this->camperPhoto && $camper->photo_path) {
                Storage::disk('public')->delete($camper->photo_path);
            }
            
            $camper->update($camperAttributes);
            $message = 'Camper updated successfully.';
        } else {
            $camperAttributes['family_id'] = $family->id;
            $camper = Camper::create($camperAttributes);
            $message = 'Camper added successfully.';
        }

        $this->syncCamperInformationSnapshot(
            $camper,
            [
                'grade' => $this->camperGrade,
                't_shirt_size' => $this->camperTShirtSize,
            ],
            $this->defaultYear()
        );

        $this->closeCamperModal();
        $this->loadStats();
        session()->flash('message', $message);
    }

    protected function defaultYear(): int
    {
        return (int) (config('annual_forms.default_year') ?? now()->year);
    }

    protected function syncCamperInformationSnapshot(Camper $camper, array $attributes, int $year): void
    {
        $snapshot = CamperInformationSnapshot::firstOrNew([
            'camper_id' => $camper->id,
            'year' => $year,
        ]);

        $existing = $snapshot->data ?? [];
        $camperData = array_merge(
            [
                'first_name' => $camper->first_name,
                'last_name' => $camper->last_name,
                'date_of_birth' => optional($camper->date_of_birth)->format('Y-m-d'),
            ],
            Arr::get($existing, 'camper', [])
        );

        if (array_key_exists('grade', $attributes)) {
            $camperData['grade'] = $attributes['grade'];
        }

        if (array_key_exists('t_shirt_size', $attributes)) {
            $camperData['t_shirt_size'] = $attributes['t_shirt_size'];
        }

        Arr::set($existing, 'camper', $camperData);

        $snapshot->fill([
            'form_version' => $snapshot->form_version ?? "{$year}.1",
            'data' => $existing,
            'data_hash' => hash('sha256', json_encode($existing)),
        ]);

        $snapshot->captured_at = now();
        $snapshot->captured_by_user_id = auth()->id();
        $snapshot->save();
    }

    public function deleteCamper($camperId)
    {
        $user = auth()->user();
        $family = $user->defaultFamily();
        
        $camper = Camper::where('id', $camperId)
            ->where('family_id', $family->id)
            ->firstOrFail();
        
        // Delete photo if exists
        if ($camper->photo_path) {
            Storage::disk('public')->delete($camper->photo_path);
        }
        
        $camper->delete();
        $this->loadStats();
        session()->flash('message', 'Camper deleted successfully.');
    }

    public function getFamiliesProperty()
    {
        $user = auth()->user();
        return $user->families()->with('campers')->get();
    }

    public function getAllCampersProperty()
    {
        $user = auth()->user();
        $families = $user->families()->with('campers')->get();
        $campers = collect();
        foreach ($families as $family) {
            $campers = $campers->concat($family->campers);
        }
        return $campers;
    }

    public function render()
    {
        return view('livewire.parent-portal.dashboard')->layout('components.layouts.app');
    }
}