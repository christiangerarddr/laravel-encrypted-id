<?php

namespace Cgdr\LaravelEncryptedId\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use UnexpectedValueException;

/**
 * Trait HasEncryptedId
 *
 * @mixin Model
 */
trait HasEncryptedId
{
    public function initializeHasEncryptedId(): void
    {
        $this->hidden = array_unique(array_merge($this->hidden, ['id']));
        $this->appends = array_unique(array_merge(['encrypted_id'], $this->appends));
    }

    public function getEncryptedIdAttribute(): string
    {
        return Crypt::encrypt($this->attributes['id']);
    }

    public static function decryptId(string $encryptedId): int
    {
        $decrypted = Crypt::decrypt($encryptedId);

        if (! ctype_digit((string) $decrypted)) {
            throw new UnexpectedValueException('Decrypted ID must be an integer.');
        }

        return (int) $decrypted;
    }
}
