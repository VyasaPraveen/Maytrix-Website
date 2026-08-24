<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class Payment extends Model
{
    protected string $table = 'payments';
    protected array $fillable = [
        'context', 'context_id', 'gateway', 'amount', 'currency', 'status',
        'gateway_ref', 'payload', 'created_at',
    ];
}
