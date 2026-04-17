<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class MagentoAdminUser extends Model implements Authenticatable, FilamentUser
{
    protected $connection = 'magento';

    protected $table = 'admin_user';

    protected $primaryKey = 'user_id';

    public $timestamps = false;

    protected $guarded = ['*'];

    protected $fillable = [];

    protected $hidden = ['password'];

    protected $appends = ['name'];

    /**
     * Get the name of the unique identifier for the user.
     */
    public function getAuthIdentifierName(): string
    {
        return $this->getKeyName();
    }

    /**
     * Get the unique identifier for the user.
     */
    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Get the password for the user.
     */
    public function getAuthPassword(): string
    {
        return $this->password;
    }

    /**
     * Get the column name for the user's password.
     */
    public function getAuthPasswordName(): string
    {
        return 'password';
    }

    /**
     * Get the user's full name from firstname and lastname.
     */
    public function getNameAttribute(): string
    {
        return trim("{$this->firstname} {$this->lastname}") ?: $this->username;
    }

    /**
     * Get the token value for the "remember me" functionality.
     */
    public function getRememberToken(): ?string
    {
        return $this->remember_token ?? null;
    }

    /**
     * Set the token value for the "remember me" functionality.
     */
    public function setRememberToken($value): void
    {
        $this->remember_token = $value;
    }

    /**
     * Get the column name for the "remember me" token.
     */
    public function getRememberTokenName(): string
    {
        return 'remember_token';
    }

    /**
     * Check if password matches Magento's hash format (bcrypt for 2.4.0+).
     * Magento 2.4.0+ uses bcrypt by default, but may have legacy md5:salt hashes.
     */
    public function hasValidPassword(string $password): bool
    {
        // Try bcrypt first (Magento 2.4.0+)
        if (password_verify($password, $this->password)) {
            return true;
        }

        // Fallback to md5:salt format for legacy entries
        if (strpos($this->password, ':') !== false) {
            [$hash, $salt] = explode(':', $this->password, 2);

            return hash_equals($hash, md5($salt.$password));
        }

        return false;
    }

    /**
     * Determine if the user can access the given Filament panel.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return (bool) $this->is_active;
    }

    public function save(array $options = []): bool
    {
        throw new \Exception('MagentoAdminUser is read-only and cannot be modified.');
    }

    public function update(array $attributes = [], array $options = []): bool
    {
        throw new \Exception('MagentoAdminUser is read-only and cannot be modified.');
    }

    public function delete(): bool
    {
        throw new \Exception('MagentoAdminUser is read-only and cannot be deleted.');
    }
}
