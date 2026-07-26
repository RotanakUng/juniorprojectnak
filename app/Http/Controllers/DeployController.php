<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DeployController extends Controller
{
    /**
     * Show the deploy page.
     */
    public function show()
    {
        return view('deploy');
    }

    /**
     * Handle the deployment execution.
     */
    public function run(Request $request)
    {
        $bodyData = json_decode($request->getContent(), true) ?? [];
        $password = $request->input('password') ?? $bodyData['password'] ?? null;

        if (empty($password)) {
            return response()->json([
                'success' => false,
                'message' => 'Password is required.',
            ], 422);
        }

        $expectedPassword = env('DEPLOY_PASSWORD', 'nakdeploy2026');

        if ($password !== $expectedPassword) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid deployment password.',
            ], 403);
        }

        $projectPath = base_path();
        $envPath = "export PATH={$projectPath}/node_modules/.bin:\$PATH:/usr/bin:/usr/local/bin;";

        $commands = [
            "{$envPath} git -C {$projectPath} pull origin master 2>&1",
            "{$envPath} export COMPOSER_ALLOW_SUPERUSER=1 && composer --working-dir={$projectPath} install --optimize-autoloader --no-dev 2>&1",
            "{$envPath} npm --prefix {$projectPath} run build 2>&1",
            "{$envPath} php {$projectPath}/artisan migrate --force 2>&1",
            "{$envPath} php {$projectPath}/artisan config:cache 2>&1",
            "{$envPath} php {$projectPath}/artisan route:cache 2>&1",
            "{$envPath} php {$projectPath}/artisan view:cache 2>&1",
            "(sleep 1 && sudo systemctl reload php8.3-fpm && sudo systemctl reload nginx) >/dev/null 2>&1 &",
        ];

        $fullCommand = implode(' && ', $commands);
        $output = shell_exec($fullCommand);

        return response()->json([
            'success' => true,
            'message' => 'Deployment executed successfully!',
            'output' => $output ?? 'Deployment completed successfully.',
        ]);
    }
}
