<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDepartementRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Vérification simplifiée
        $user = $this->user();
        return $user && $user->employe && $user->employe->role === 'admin';
    }
    
    public function rules(): array
    {
        return [
            'nom' => 'required|string|max:100|unique:departements,nom',
            'description' => 'nullable|string|max:500',
        ];
    }
}