<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'organization_id', 'user_id', 'action', 'entity_type', 
        'entity_id', 'description', 'old_values', 'new_values'
    ];

    protected $casts = ['old_values' => 'array', 'new_values' => 'array'];

    public function organization() { return $this->belongsTo(Organization::class); }
    public function user() { return $this->belongsTo(User::class); }

    public static function log($orgId, $action, $entityType, $entityId = null, $description = null, $old = [], $new = [])
    {
        return self::create([
            'organization_id' => $orgId,
            'user_id' => auth()->id() ?? null,
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'description' => $description,
            'old_values' => $old,
            'new_values' => $new,
        ]);
    }

    public static function created($orgId, $entityType, $entity, $description = null)
    {
        self::log($orgId, 'created', $entityType, $entity->id, $description ?? "Created new {$entityType}");
    }

    public static function updated($orgId, $entityType, $entity, $old, $new)
    {
        self::log($orgId, 'updated', $entityType, $entity->id, "Updated {$entityType}", $old, $new);
    }

    public static function deleted($orgId, $entityType, $entityId, $description = null)
    {
        self::log($orgId, 'deleted', $entityType, $entityId, $description ?? "Deleted {$entityType}");
    }
}
