<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isInventoryManager(): bool
    {
        return $this->role === 'inventory_manager';
    }

    public function isCashier(): bool
    {
        return $this->role === 'cashier';
    }

    public function hasRole(string|array $roles): bool
    {
        if ($this->isAdmin()) {
            return true; // Admin has superuser access to all roles
        }

        if (is_string($roles)) {
            $roles = explode(',', $roles);
        }

        return in_array($this->role, $roles);
    }

    public function getRoleFormattedAttribute(): string
    {
        return match($this->role) {
            'admin' => 'Administrator / Owner',
            'inventory_manager' => 'Inventory Manager',
            'cashier' => 'Point of Sale Cashier',
            default => ucwords(str_replace('_', ' ', $this->role ?? 'Staff')),
        };
    }

    public function getRoleBadgeAttribute(): array
    {
        return match($this->role) {
            'admin' => ['label' => 'Admin', 'class' => 'bg-purple-100 text-purple-800 border-purple-200'],
            'inventory_manager' => ['label' => 'Inventory Clerk', 'class' => 'bg-blue-100 text-blue-800 border-blue-200'],
            'cashier' => ['label' => 'Cashier', 'class' => 'bg-emerald-100 text-emerald-800 border-emerald-200'],
            default => ['label' => ucfirst($this->role ?? 'User'), 'class' => 'bg-gray-100 text-gray-800 border-gray-200'],
        };
    }
}
