<?php
// actions/ai_proxy.php
require_once '../settings/core.php';
require_once '../classes/incident_class.php';

header('Content-Type: application/json');

// Get the user message
$input = json_decode(file_get_contents('php://input'), true);
$userMessage = $input['message'] ?? '';
$isSilent = $input['silent'] ?? false;
$user_id = $_SESSION['id'] ?? null;

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

// Prioritize native getenv() for deployment support (Docker/Heroku/cPanel)
$apiKey = getenv('GEMINI_API_KEY') ?: ($_ENV['GEMINI_API_KEY'] ?? '') ?: 'mock-key';

$systemPrompt = "You are an empathetic AI listener for GBVAid, supporting survivors in Ghana. 
Your tone is gentle and non-judgmental. 
1. If the user mentions or needs DOVVSU, recommend them to use the legal services of DOVVSU. Legal help or medical assistance, recommend them to use the resources tips page.
2. If in danger, recommend using the SOS button.";

if ($isSilent) {
    $db = new db_conn();
    $sql = "INSERT INTO ai_logs (user_id, user_message, ai_response, sentiment_flag) VALUES (?, ?, ?, ?)";
    $db->db_query($sql, [$user_id, $userMessage, 'SILENT_VENT', 'NEUTRAL']);
    echo json_encode(['status' => 'logged_silently']);
    exit;
}

$data = [
    'system_instruction' => [
        'parts' => [
            ['text' => $systemPrompt]
        ]
    ],
    'contents' => [
        [
            'parts' => [
                ['text' => $userMessage]
            ]
        ]
    ],
    'generationConfig' => [
        'temperature' => 0.7
    ]
];

// Determine the URL
$url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=' . $apiKey;

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL verification for local development environments
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json'
]);

$response = curl_exec($ch);
$err = curl_error($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($err) {
    echo json_encode(['reply' => 'Connection error.', 'debug' => $err]);
    exit;
}

$resData = json_decode($response, true);

if ($httpCode !== 200 || isset($resData['error'])) {
    // Fallback to simulated responses if API quota is exceeded or key is invalid
    $fallbackReplies = [
        "I hear you, and I want you to know you're not alone in this.",
        "Thank you for sharing that with me. This is a safe space.",
        "It sounds like you're going through a lot. I'm here to listen.",
        "Your feelings are valid. Please take all the time you need to vent.",
        "I am listening. Sometimes just letting it out can help."
    ];
    $aiReply = $fallbackReplies[array_rand($fallbackReplies)];
    
    // Log the simulated response
    $db = new db_conn();
    $sql = "INSERT INTO ai_logs (user_id, user_message, ai_response, sentiment_flag) VALUES (?, ?, ?, ?)";
    $db->db_query($sql, [$user_id, $userMessage, $aiReply . ' (Simulated)', 'NEUTRAL']);

    echo json_encode([
        'reply' => $aiReply,
        'debug_error' => $resData['error']['message'] ?? 'Unknown API Error HTTP Code: ' . $httpCode
    ]);
    exit;
}

$aiReply = $resData['candidates'][0]['content']['parts'][0]['text'] ?? 'I am listening.';

$db = new db_conn();
$sql = "INSERT INTO ai_logs (user_id, user_message, ai_response, sentiment_flag) VALUES (?, ?, ?, ?)";
$db->db_query($sql, [$user_id, $userMessage, $aiReply, 'NEUTRAL']);

echo json_encode(['reply' => $aiReply]);