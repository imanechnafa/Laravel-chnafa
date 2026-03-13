<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEmployeRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        return $user && $user->employe && $user->employe->role === 'admin';
    }
    
    public function rules(): array
    {
        return [
            'nom' => 'required|string|max:50',
            'prenom' => 'required|string|max:50',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'departement_id' => 'required|exists:departements,id',
            'matricule' => 'required|string|max:20|unique:employes,matricule',
            'date_embauche' => 'required|date',
            'role' => ['required', Rule::in(['employe', 'manager', 'admin'])],
            'solde_conge' => 'nullable|integer|min:0|max:100',
        ];
    }
}