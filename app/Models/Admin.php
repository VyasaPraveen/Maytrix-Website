<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class Admin extends Model
{
    protected string $table = 'admins';
    protected string $connection = 'admin';
    protected array $fillable = ['name', 'email', 'password_hash', 'role', 'is_active', 'last_login_at', 'created_at'];
}
