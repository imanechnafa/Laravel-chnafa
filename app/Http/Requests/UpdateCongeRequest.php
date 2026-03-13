<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCongeRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        return $user && $user->employe && $user->employe->role === 'employe';
    }
    
    public function rules(): array
    {
        return [
            'type_conge_id' => 'required|exists:type_conges,id',
            'date_debut' => 'required|date|after_or_equal:today',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'motif' => 'required|string|min:10|max:1000',
        ];
    }
}