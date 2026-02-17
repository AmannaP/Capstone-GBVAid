<?php
// actions/ai_proxy.php
require_once '../settings/core.php';
require_once '../classes/incident_class.php';

header('Content-Type: application/json');

// Get the user message
$input = json_decode(file_get_contents('php://input'), true);
$userMessage = $input['message'] ?? '';
$isSilent = $input['silent'] ?? false;
$user_id = $_SESSION['user_id'] ?? null;

if (empty($userMessage)) {
    echo json_encode(['reply' => 'I am listening, but I didn\'t catch that message.']);
    exit;
}

function loadEnv($path) {
    if (!file_exists($path)) return;
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (empty(trim($line)) || strpos(trim($line), '#') === 0) continue;
        list($name, $value) = explode('=', $line, 2);
        $_ENV[trim($name)] = trim($value);
    }
}

loadEnv(__DIR__ . '/../.env');

// TYPO FIX: Changed OPEN_API_KEY to OPENAI_API_KEY to match typical .env naming
$apiKey = $_ENV['OPENAI_API_KEY'] ?? 'sk-proj-DUMMY_FOR_GITHUB';

$systemPrompt = "You are an empathetic AI listener for GBVAid, supporting survivors in Ghana. 
Your tone is gentle and non-judgmental. 
1. If the user needs DOVVSU, legal help, or medical assistance, guide them specifically to Ghanaian resources.
2. If in danger, use the SOS button.";

if ($isSilent) {
    $db = new db_conn();
    $sql = "INSERT INTO ai_logs (user_id, user_message, ai_response, sentiment_flag) VALUES (?, ?, ?, ?)";
    $db->db_query($sql, [$user_id, $userMessage, 'SILENT_VENT', 'NEUTRAL']);
    echo json_encode(['status' => 'logged_silently']);
    exit;
}

$data = [
    'model' => 'gpt-3.5-turbo',
    'messages' => [
        ['role' => 'system', 'content' => $systemPrompt],
        ['role' => 'user', 'content' => $userMessage]
    ],
    'temperature' => 0.7
];

$ch = curl_init('https://api.openai.com/v1/chat/completions');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // CRITICAL: Fixes XAMPP connection issues
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $apiKey
]);

$response = curl_exec($ch);
$err = curl_error($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE); // Added this missing line
curl_close($ch);

if ($err) {
    echo json_encode(['reply' => 'Connection error.', 'debug' => $err]);
    exit;
}

$resData = json_decode($response, true);

if ($httpCode !== 200) {
    echo json_encode([
        'reply' => 'My connection is currently restricted.',
        'debug_error' => $resData['error']['message'] ?? 'Unknown API Error'
    ]);
    exit;
}

$aiReply = $resData['choices'][0]['message']['content'] ?? 'I am listening.';

$db = new db_conn();
$sql = "INSERT INTO ai_logs (user_id, user_message, ai_response, sentiment_flag) VALUES (?, ?, ?, ?)";
$db->db_query($sql, [$user_id, $userMessage, $aiReply, 'NEUTRAL']);

echo json_encode(['reply' => $aiReply]);