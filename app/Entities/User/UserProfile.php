<?php

namespace App\Entities\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $user_id
 * @property string $last_name
 * @property string $phone
 * @property string $role
 * @property User $user
 *
 */
class UserProfile extends Model
{
    public const ROLE_USER = 'user';
    public const ROLE_MODERATOR = 'moderator';
    public const ROLE_ADMIN = 'admin';

    public $timestamps = false;

    protected $table = 'user_profiles';

    protected $fillable = [
        'last_name', 'phone', 'role'
    ];

    public static function roleList(): array
    {
        return [
            self::ROLE_USER      => 'Покупатель',
            self::ROLE_ADMIN     => 'Администратор',
            self::ROLE_MODERATOR => 'Модератор'
        ];
    }

    public static function getCurrentRole($role):array
    {
        $class = match ($role) {
            self::ROLE_ADMIN => 'bg-danger',
            self::ROLE_MODERATOR => 'bg-warning',
            default => 'bg-info',
        };
        return ['name' => self::roleList()[$role], 'class' => $class];
    }

    public function edit(string $last_name, string $phone)
    {
        $this->update(['last_name' => $last_name, 'phone' => $phone]);
    }

    public function isModerator():bool
    {
        return $this->role === self::ROLE_MODERATOR;
    }

    public function isAdmin():bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isFilledProfile(): bool
    {
        $user = Auth::user();
        return !empty($user->name) && !empty($this->last_name) && !empty($this->phone);
    }

    public function changeRole($role):void
    {
        if (!array_key_exists($role, self::roleList())) {
            throw new \InvalidArgumentException('Неизвестная роль "' . $role . '"');
        }
        if ($this->role === $role) {
            throw new \DomainException('Данная роль уже назначена пользователю');
        }
        $this->update(['role' => $role]);
    }

    public function user():HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
