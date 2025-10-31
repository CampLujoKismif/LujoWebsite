<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class CustomEmailVerificationRequest extends FormRequest
{
    /**
     * Prepare the request for validation.
     */
    protected function prepareForValidation(): void
    {
        // If user is not authenticated, load the user from the route ID
        if (!$this->user()) {
            $user = User::withTrashed()->find($this->route('id'));
            
            if ($user) {
                // Set the user resolver so $this->user() works
                $this->setUserResolver(function () use ($user) {
                    return $user;
                });
            }
        }
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        \Log::info('CustomEmailVerificationRequest: authorize() called', [
            'user_id' => $this->user()?->id,
            'route_id' => $this->route('id'),
            'route_hash' => $this->route('hash'),
            'user_email' => $this->user()?->email,
        ]);

        if (! $this->user()) {
            \Log::warning('CustomEmailVerificationRequest: No user found');
            return false;
        }

        // Check if the user ID matches
        if (! hash_equals((string) $this->user()->getKey(), (string) $this->route('id'))) {
            \Log::warning('CustomEmailVerificationRequest: User ID mismatch', [
                'user_key' => $this->user()->getKey(),
                'route_id' => $this->route('id')
            ]);
            return false;
        }

        // Check if the email hash matches
        if (! hash_equals(sha1($this->user()->getEmailForVerification()), (string) $this->route('hash'))) {
            \Log::warning('CustomEmailVerificationRequest: Email hash mismatch', [
                'expected_hash' => sha1($this->user()->getEmailForVerification()),
                'route_hash' => $this->route('hash')
            ]);
            return false;
        }

        \Log::info('CustomEmailVerificationRequest: Authorized successfully');
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }
}
