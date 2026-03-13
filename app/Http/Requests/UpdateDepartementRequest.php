<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDepartementRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        return $user && $user->employe && $user->employe->role === 'admin';
    }
    
    public function rules(): array
    {
        $departementId = $this->route('departement')->id;
        
        return [
            'nom' => 'required|string|max:100|unique:departements,nom,' . $departementId,
            'description' => 'nullable|string|max:500',
        ];
    }
}