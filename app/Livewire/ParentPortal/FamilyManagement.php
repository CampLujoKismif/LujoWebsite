<?php

namespace App\Livewire\ParentPortal;

use App\Models\Family;
use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class FamilyManagement extends Component
{
    use WithFileUploads;

    public $families;
    public $selectedFamily;
    public $showAddFamilyModal = false;
    public $showEditFamilyModal = false;
    public $showAddMemberModal = false;
    
    // Family form fields
    public $familyName;
    public $phone;
    public $address;
    public $city;
    public $state;
    public $zipCode;
    public $emergencyContactName;
    public $emergencyContactPhone;
    public $emergencyContactRelationship;
    
    // Add member form fields
    public $memberEmail;
    public $memberRole = 'parent';
    public $memberName;

    protected $rules = [
        'familyName' => 'required|string|max:255',
        'phone' => 'nullable|string|max:20',
        'address' => 'nullable|string|max:255',
        'city' => 'nullable|string|max:100',
        'state' => 'nullable|string|max:2',
        'zipCode' => 'nullable|string|max:10',
        'emergencyContactName' => 'nullable|string|max:255',
        'emergencyContactPhone' => 'nullable|string|max:20',
        'emergencyContactRelationship' => 'nullable|string|max:100',
        'memberEmail' => 'required|email|exists:users,email',
        'memberRole' => 'required|in:parent,guardian,emergency_contact',
        'memberName' => 'required|string|max:255',
    ];

    public function mount()
    {
        $this->loadFamilies();
    }

    public function loadFamilies()
    {
        $user = auth()->user();
        $this->families = $user->families()->with(['users', 'campers'])->get();
    }

    public function openAddFamilyModal()
    {
        $this->resetFamilyForm();
        $this->showAddFamilyModal = true;
    }

    public function openEditFamilyModal(Family $family)
    {
        $this->selectedFamily = $family;
        $this->familyName = $family->name;
        $this->phone = $family->phone;
        $this->address = $family->address;
        $this->city = $family->city;
        $this->state = $family->state;
        $this->zipCode = $family->zip_code;
        $this->emergencyContactName = $family->emergency_contact_name;
        $this->emergencyContactPhone = $family->emergency_contact_phone;
        $this->emergencyContactRelationship = $family->emergency_contact_relationship;
        $this->showEditFamilyModal = true;
    }

    public function openAddMemberModal(Family $family)
    {
        $this->selectedFamily = $family;
        $this->resetMemberForm();
        $this->showAddMemberModal = true;
    }

    public function createFamily()
    {
        $this->validate([
            'familyName' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:2',
            'zipCode' => 'nullable|string|max:10',
            'emergencyContactName' => 'nullable|string|max:255',
            'emergencyContactPhone' => 'nullable|string|max:20',
            'emergencyContactRelationship' => 'nullable|string|max:100',
        ]);

        DB::transaction(function () {
            $family = Family::create([
                'name' => $this->familyName,
                'owner_user_id' => auth()->id(),
                'phone' => $this->phone,
                'address' => $this->address,
                'city' => $this->city,
                'state' => $this->state,
                'zip_code' => $this->zipCode,
                'emergency_contact_name' => $this->emergencyContactName,
                'emergency_contact_phone' => $this->emergencyContactPhone,
                'emergency_contact_relationship' => $this->emergencyContactRelationship,
            ]);

            // Add the current user as the family owner
            $family->users()->attach(auth()->id(), ['role_in_family' => 'parent']);
        });

        $this->showAddFamilyModal = false;
        $this->resetFamilyForm();
        $this->loadFamilies();
        $this->dispatch('family-created');
    }

    public function updateFamily()
    {
        $this->validate([
            'familyName' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:2',
            'zipCode' => 'nullable|string|max:10',
            'emergencyContactName' => 'nullable|string|max:255',
            'emergencyContactPhone' => 'nullable|string|max:20',
            'emergencyContactRelationship' => 'nullable|string|max:100',
        ]);

        $this->selectedFamily->update([
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

        $this->showEditFamilyModal = false;
        $this->resetFamilyForm();
        $this->loadFamilies();
        $this->dispatch('family-updated');
    }

    public function addFamilyMember()
    {
        $this->validate([
            'memberEmail' => 'required|email|exists:users,email',
            'memberRole' => 'required|in:parent,guardian,emergency_contact',
            'memberName' => 'required|string|max:255',
        ]);

        $user = User::where('email', $this->memberEmail)->first();
        
        if (!$user) {
            $this->addError('memberEmail', 'User not found with this email address.');
            return;
        }

        // Check if user is already a member of this family
        if ($this->selectedFamily->users()->where('user_id', $user->id)->exists()) {
            $this->addError('memberEmail', 'This user is already a member of this family.');
            return;
        }

        $this->selectedFamily->users()->attach($user->id, [
            'role_in_family' => $this->memberRole
        ]);

        $this->showAddMemberModal = false;
        $this->resetMemberForm();
        $this->loadFamilies();
        $this->dispatch('member-added');
    }

    public function removeFamilyMember($userId)
    {
        $user = auth()->user();
        
        // Only allow removal if the current user is the family owner
        if ($this->selectedFamily->owner_user_id !== $user->id) {
            $this->dispatch('error', 'Only the family owner can remove members.');
            return;
        }

        // Don't allow removing the family owner
        if ($userId == $this->selectedFamily->owner_user_id) {
            $this->dispatch('error', 'Cannot remove the family owner.');
            return;
        }

        $this->selectedFamily->users()->detach($userId);
        $this->loadFamilies();
        $this->dispatch('member-removed');
    }

    public function resetFamilyForm()
    {
        $this->familyName = '';
        $this->phone = '';
        $this->address = '';
        $this->city = '';
        $this->state = '';
        $this->zipCode = '';
        $this->emergencyContactName = '';
        $this->emergencyContactPhone = '';
        $this->emergencyContactRelationship = '';
        $this->selectedFamily = null;
    }

    public function resetMemberForm()
    {
        $this->memberEmail = '';
        $this->memberRole = 'parent';
        $this->memberName = '';
    }

    public function render()
    {
        return view('livewire.parent-portal.family-management')->layout('components.layouts.app');
    }
}
