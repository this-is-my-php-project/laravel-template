<?php

namespace App\Http\Middleware;

use App\Models\Profile;
use Closure;
use Illuminate\Http\Request;

class BelongToWorkspace
{
    public function handle(Request $request, Closure $next)
    {
        $request->validate([
            'workspace_id' => 'required|exists:workspaces,id'
        ]);

        $profile = Profile::where('user_id', $request->user()->id)
            ->where('workspace_id', $request->workspace_id)
            ->first();

        if (empty($profile)) {
            return response()->json([
                'status' => 401,
                'message' => 'You do not belong to this workspace',
                'data' => []
            ]);
        }

        return $next($request);
    }
}
