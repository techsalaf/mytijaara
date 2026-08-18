<?php
// =============================================================
//   MYTIJAARA — Laravel Auto Deployment Script
//   Path: /home/iqacibco/mytijaara/deploy/git-deploy.php
// =============================================================

// CONFIGURATION
$config = [
    'secret' => '2025myTijaaraSuperSecretToken',
    'repo_path' => '/home/iqacibco/mytijaara',
    'branch' => 'main',
    'git_path' => '/usr/local/cpanel/3rdparty/lib/path-bin/git',
    'php_path' => '/usr/local/bin/php',
    'log_file' => __DIR__ . '/deploy-log.txt',
    'max_log_size' => 5 * 1024 * 1024, // 5MB
    'allowed_ips' => [], // Optional: GitHub webhook IPs for extra security

    'post_deploy_commands' => [
        // Regenerate package discovery cache (bootstrap/cache/packages.php).
        // The server has no composer, and vendor/ is committed, so this is
        // what keeps service providers like Inertia registered correctly.
        '{{php}} artisan package:discover',

        // Clear stale Laravel caches (config/routes/views/events/bootstrap)
        '{{php}} artisan optimize:clear',

        // Regenerate caches (config, routes, events, views)
        '{{php}} artisan optimize',

        // Permissions
        'chmod -R 775 storage bootstrap/cache',

        // Migrations
        '{{php}} artisan migrate --force',
    ]
];


// =============================================================
// UTILITY FUNCTIONS
// =============================================================
function logDeploy($message, $type = 'INFO')
{
    global $config;
    $timestamp = date('Y-m-d H:i:s');
    $entry = "[$timestamp] [$type] $message\n";

    if (file_exists($config['log_file']) && filesize($config['log_file']) > $config['max_log_size']) {
        rename($config['log_file'], $config['log_file'] . '.' . time() . '.old');
    }

    file_put_contents($config['log_file'], $entry, FILE_APPEND);
}

function respondError($code, $message)
{
    http_response_code($code);
    logDeploy($message, 'ERROR');
    exit(json_encode(['error' => $message, 'timestamp' => time()]));
}

/**
 * Run a shell command, log it, and abort the deploy if it fails.
 */
function runCommand($cmd)
{
    global $config;
    logDeploy("Running: $cmd");

    $output = [];
    $code = 0;
    exec($cmd . " 2>&1", $output, $code);

    logDeploy("Exit code: $code");
    logDeploy("Output:\n" . implode("\n", $output));

    if ($code !== 0) {
        respondError(500, "Command failed: $cmd");
    }
}


// =============================================================
// 1. SECURITY CHECKS
// =============================================================

// IP whitelist (optional)
if (!empty($config['allowed_ips'])) {
    $clientIP = $_SERVER['REMOTE_ADDR'] ?? '';
    if (!in_array($clientIP, $config['allowed_ips'])) {
        respondError(403, "Forbidden: IP $clientIP not allowed");
    }
}

// Request method must be POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respondError(405, 'Method Not Allowed: Only POST requests accepted');
}

// Retrieve payload
$payload = file_get_contents('php://input');
$signature = $_SERVER['HTTP_X_HUB_SIGNATURE_256'] ?? '';

// Verify GitHub signature
$expectedSig = 'sha256=' . hash_hmac('sha256', $payload, $config['secret']);

if (!hash_equals($expectedSig, $signature)) {
    respondError(403, 'Invalid signature — webhook authentication failed.');
}


// Decode JSON
$data = json_decode($payload, true);
if (!$data) {
    respondError(400, 'Invalid JSON payload');
}


// =============================================================
// 2. EVENT & BRANCH CHECKS
// =============================================================
$event = $_SERVER['HTTP_X_GITHUB_EVENT'] ?? '';
$branch = basename($data['ref'] ?? '');

logDeploy("Webhook received: Event=$event, Branch=$branch");

// Only allow push to the configured branch
if ($event !== 'push') {
    exit(json_encode(['status' => 'ignored', 'reason' => 'Not a push event']));
}

if ($branch !== $config['branch']) {
    exit(json_encode(['status' => 'ignored', 'reason' => "Branch mismatch ($branch)"]));
}

// Acknowledge the webhook immediately so GitHub never sees a timeout.
// The actual deploy continues running after the response is flushed.
ignore_user_abort(true);
http_response_code(200);
header('Content-Type: application/json');
echo json_encode(['status' => 'accepted', 'message' => 'Deploy queued', 'timestamp' => time()]);

if (function_exists('fastcgi_finish_request')) {
    fastcgi_finish_request();
} else {
    while (ob_get_level() > 0) {
        ob_end_flush();
    }
    flush();
}


// =============================================================
// 3. BEGIN DEPLOYMENT
// =============================================================
logDeploy("=== Starting Deployment ===");

if (!chdir($config['repo_path'])) {
    respondError(500, "Could not change directory to repo path");
}

// Fetch, clean, and hard-reset the working tree to the remote branch.
// Ignored files (like .env) are preserved by using `-fd` instead of `-fdx`.
runCommand("{$config['git_path']} fetch origin {$config['branch']}");
runCommand("{$config['git_path']} clean -fd");
runCommand("{$config['git_path']} reset --hard origin/{$config['branch']}");


// =============================================================
// 4. RUN POST-DEPLOY COMMANDS (Laravel)
// =============================================================
logDeploy("=== Running Laravel Post-Deploy Commands ===");

foreach ($config['post_deploy_commands'] as $cmd) {
    $cmd = str_replace('{{php}}', $config['php_path'], $cmd);
    logDeploy("Executing: $cmd");
    runCommand($cmd);
}


// =============================================================
// 5. SUCCESS RESPONSE
// =============================================================
logDeploy("=== Deployment Completed Successfully ===", 'SUCCESS');

echo json_encode([
    'status' => 'success',
    'message' => 'Laravel deployment completed successfully',
    'branch' => $branch,
    'timestamp' => time()
]);
?>
