<?php

namespace App\Observers;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Artisan;

class UserObserver
{
    public function created(User $user): void
    {
        // Assign role user (ID 2) dan semua permissionnya
        $role = Role::findById(2); // role 'user'
        $user->assignRole($role);
        
        $user->syncPermissions($role->permissions);
    }

    public function updated(User $user): void
    {
        // Cek apakah role_id berubah
        if ($user->isDirty('role_id')) {
            // Hapus semua role yang ada
            $user->roles()->detach();
            
            // Assign role baru
            $role = Role::findById($user->role_id);
            $user->assignRole($role);
            
            // Sync permissions dengan role baru
            $user->syncPermissions($role->permissions);
        }
        
        // Reset permission cache
        Artisan::call('permission:cache-reset');
    }

    
}