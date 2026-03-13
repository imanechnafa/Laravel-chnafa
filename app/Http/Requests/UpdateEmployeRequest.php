<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEmployeRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        return $user && $user->employe && $user->employe->role === 'admin';
    }
    
    public function rules(): array
    {
        $employeId = $this->route('employe')->id;
        $userId = $this->route('employe')->user_id;
        
        return [
            'nom' => 'required|string|max:50',
            'prenom' => 'required|string|max:50',
            'email' => 'required|email|unique:users,email,' . $userId,
            'password' => 'nullable|string|min:8|confirmed',
            'departement_id' => 'required|exists:departements,id',
            'matricule' => 'required|string|max:20|unique:employes,matricule,' . $employeId,
            'date_embauche' => 'required|date',
            'role' => ['required', Rule::in(['employe', 'manager', 'admin'])],
            'solde_conge' => 'nullable|integer|min:0|max:100',
        ];
    }
}