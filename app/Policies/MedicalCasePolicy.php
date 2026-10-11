<?php

namespace App\Policies;

use App\Models\MedicalCase;
use App\Models\User;

class MedicalCasePolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['Admin', 'Viewer'], true);
    }

    public function view(User $user, MedicalCase $medicalCase): bool
    {
        return in_array($user->role, ['Admin', 'Viewer'], true);
    }

    public function create(User $user): bool
    {
        return $user->role === 'Admin';
    }

    public function update(User $user, MedicalCase $medicalCase): bool
    {
        return $user->role === 'Admin';
    }

    public function delete(User $user, MedicalCase $medicalCase): bool
    {
        return $user->role === 'Admin';
    }
}
