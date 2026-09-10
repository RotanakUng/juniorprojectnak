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
        @set_time_limit(300);
        @ignore_user_abort(true);

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
        $envPrefix = "export PATH={$projectPath}/node_modules/.bin:\$PATH:/usr/bin:/usr/local/bin";

        $steps = [
            'Git Pull' => "{$envPrefix} && git -C {$projectPath} pull origin master 2>&1",
            'Composer Dependencies' => "{$envPrefix} && export COMPOSER_ALLOW_SUPERUSER=1 && composer --working-dir={$projectPath} install --optimize-autoloader --no-dev 2>&1",
            'Frontend Build' => "{$envPrefix} && npm --prefix {$projectPath} run build 2>&1",
            'Database Migrations' => "{$envPrefix} && php {$projectPath}/artisan migrate --force 2>&1",
            'Configuration Cache' => "{$envPrefix} && php {$projectPath}/artisan config:cache 2>&1",
            'Route Cache' => "{$envPrefix} && php {$projectPath}/artisan route:cache 2>&1",
            'View Cache' => "{$envPrefix} && php {$projectPath}/artisan view:cache 2>&1",
        ];

        $outputLog = [];
        $hasError = false;
        $failedStep = null;

        foreach ($steps as $stepName => $cmd) {
            $outputLog[] = "--- Step: {$stepName} ---";
            $cmdOutput = [];
            $exitCode = 0;
            exec($cmd, $cmdOutput, $exitCode);

            $outputLog[] = implode("\n", $cmdOutput);

            if ($exitCode !== 0) {
                $hasError = true;
                $failedStep = $stepName;
                break;
            }
        }

        $fullOutput = implode("\n", $outputLog);

        if ($hasError) {
            return response()->json([
                'success' => false,
                'message' => "Deployment failed at step: {$failedStep}",
                'output' => $fullOutput,
            ], 500);
        }

        // Gracefully reload php-fpm and nginx in the background after the response finishes
        shell_exec('(sleep 1 && sudo systemctl reload php8.3-fpm && sudo systemctl reload nginx) >/dev/null 2>&1 &');

        return response()->json([
            'success' => true,
            'message' => 'Deployment executed successfully!',
            'output' => $fullOutput,
        ]);
    }
}
