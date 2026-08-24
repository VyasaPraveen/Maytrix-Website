<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class ContactMessage extends Model
{
    protected string $table = 'contact_messages';
    protected array $fillable = ['name', 'email', 'country', 'curriculum', 'message', 'is_read', 'created_at'];

    public function unreadCount(): int
    {
        return $this->count(['is_read' => 0]);
    }
}
