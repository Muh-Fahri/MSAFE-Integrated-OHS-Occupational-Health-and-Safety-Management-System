<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\AuditTable;

class Menu extends Model
{
    use AuditTable;

    protected $fillable = [
        'menu_name',
        'url',
        'parent_id',
        'icon',
        'order',
        'available_actions',
        'is_manual_override',
    ];

    protected $casts = [
        'available_actions' => 'array',
        'is_manual_override' => 'boolean',
    ];

    // Relationship: Menu punya parent
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    // Relationship: Menu punya banyak children
    public function children(): HasMany
    {
        return $this->hasMany(Menu::class, 'parent_id')->orderBy('order');
    }

    // Helper: Check apakah menu ini parent
    public function isParent(): bool
    {
        return is_null($this->parent_id);
    }

    // Helper: Check apakah menu ini child
    public function isChild(): bool
    {
        return !is_null($this->parent_id);
    }

    // Helper: Get available actions for this menu
    public function getAvailableActions(): array
    {
        // Jika available_actions null atau empty, return all actions (backward compatibility)
        if (empty($this->available_actions)) {
            return ['create', 'edit', 'delete', 'show', 'export', 'import'];
        }

        // Casting sudah handle conversion, tapi untuk safety
        return is_array($this->available_actions)
            ? $this->available_actions
            : json_decode($this->available_actions, true) ?? ['create', 'edit', 'delete', 'show', 'export', 'import'];
    }

    // Helper: Check if action is available
    public function hasAction(string $action): bool
    {
        return in_array($action, $this->getAvailableActions());
    }

    // Helper: Get all possible actions
    public static function getAllActions(): array
    {
        return ['create', 'edit', 'delete', 'show', 'export', 'import'];
    }

    // Helper: Auto-detect and set available actions from route
    public function autoDetectActions(): array
    {
        if (empty($this->url) || $this->url === '#') {
            return ['show']; // Parent menus default to show only
        }

        $detected = \App\Services\RouteActionDetector::detectFromRouteName($this->url);

        // If no routes found, return default based on menu type
        if (empty($detected)) {
            return $this->isParent() ? ['show'] : self::getAllActions();
        }

        return $detected;
    }

    // Helper: Sync available actions (auto-detect or manual)
    public function syncAvailableActions(bool $forceManual = false, array $manualActions = []): void
    {
        if ($forceManual) {
            $this->available_actions    = $manualActions;
            $this->is_manual_override   = true;
        } else {
            $this->available_actions    = $this->autoDetectActions();
            $this->is_manual_override   = false;
        }
    }
}
