<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class Student extends Model
{
    protected string $table = 'students';
    protected array $fillable = ['name', 'email', 'phone', 'country', 'timezone', 'notes', 'created_at'];

    /** Find by email or create a new student record. */
    public function findOrCreate(array $data): int
    {
        $existing = $this->findBy('email', strtolower(trim($data['email'])));
        if ($existing) {
            return (int) $existing['id'];
        }
        $data['email'] = strtolower(trim($data['email']));
        return $this->create($data);
    }
}
