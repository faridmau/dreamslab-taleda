<?php

namespace App\Auth;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable;

class MagentoUserProvider extends EloquentUserProvider
{
    /**
     * Validate a user against the given credentials.
     */
    public function validateCredentials(Authenticatable $user, array $credentials): bool
    {
        $plain = $credentials['password'];

        // Use the model's custom password verification if available
        if (method_exists($user, 'hasValidPassword')) {
            return $user->hasValidPassword($plain);
        }

        // Fallback to default hash verification
        return $this->hasher->check($plain, $user->getAuthPassword());
    }
}
