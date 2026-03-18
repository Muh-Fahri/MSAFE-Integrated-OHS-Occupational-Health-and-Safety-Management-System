<?php

namespace App\Services;

use Illuminate\Support\Facades\Route;

class RouteActionDetector
{
    /**
     * Map of route suffixes to permission actions
     */
    protected static $actionMap = [
        'index'   => 'show',
        'show'    => 'show',
        'create'  => 'create',
        'store'   => 'create',
        'edit'    => 'edit',
        'update'  => 'edit',
        'destroy' => 'delete',
        'delete'  => 'delete',
        'export'  => 'export',
        'import'  => 'import',
    ];

    /**
     * Detect available actions from a route name
     *
     * @param string $routeName e.g., "products.index"
     * @return array Available actions
     */
    public static function detectFromRouteName(string $routeName): array
    {
        if (empty($routeName) || $routeName === '#') {
            return [];
        }

        // Extract base route name (e.g., "products" from "products.index")
        $parts = explode('.', $routeName);

        if (count($parts) < 2) {
            return [];
        }

        $baseRoute          = $parts[0];
        $availableActions   = [];

        // Check each possible route suffix
        foreach (self::$actionMap as $suffix => $action) {
            $fullRouteName = "{$baseRoute}.{$suffix}";

            if (Route::has($fullRouteName)) {
                if (!in_array($action, $availableActions)) {
                    $availableActions[] = $action;
                }
            }
        }

        return $availableActions;
    }

    /**
     * Get all possible actions
     */
    public static function getAllActions(): array
    {
        return ['create', 'edit', 'delete', 'show', 'export', 'import'];
    }

    /**
     * Get detailed route information for debugging
     *
     * @param string $routeName
     * @return array
     */
    public static function getRouteDetails(string $routeName): array
    {
        if (empty($routeName) || $routeName === '#') {
            return [
                'detected_actions'  => [],
                'found_routes'      => [],
                'missing_routes'    => [],
            ];
        }

        $parts = explode('.', $routeName);

        if (count($parts) < 2) {
            return [
                'detected_actions'  => [],
                'found_routes'      => [],
                'missing_routes'    => [],
            ];
        }

        $baseRoute          = $parts[0];
        $foundRoutes        = [];
        $missingRoutes      = [];
        $detectedActions    = [];

        foreach (self::$actionMap as $suffix => $action) {
            $fullRouteName = "{$baseRoute}.{$suffix}";

            if (Route::has($fullRouteName)) {
                $foundRoutes[] = $fullRouteName;
                if (!in_array($action, $detectedActions)) {
                    $detectedActions[] = $action;
                }
            } else {
                $missingRoutes[] = $fullRouteName;
            }
        }

        return [
            'detected_actions'  => $detectedActions,
            'found_routes'      => $foundRoutes,
            'missing_routes'    => $missingRoutes,
        ];
    }

    /**
     * Check if a specific action is available for a route
     */
    public static function hasAction(string $routeName, string $action): bool
    {
        $actions = self::detectFromRouteName($routeName);
        return in_array($action, $actions);
    }
}
