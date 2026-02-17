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
    echo json_encode(['error' => 'Message is empty']);
    exit;
}

// My OpenAI API Key 
$apiKey = getenv('API_KEY');

// Define the AI's "Personality" (System Prompt)
$systemPrompt = "You are an empathetic AI listener for GBVAid, supporting survivors in Ghana. 
Your tone is gentle and non-judgmental. 
1. If the user needs legal or police help, mention DOVVSU (Domestic Violence and Victims Support Unit).
2. If they need medical help, suggest the nearest government hospital.
3. If they need a safe space, mention The Ark Foundation or similar shelters in Ghana.
4. If they are in immediate danger, tell them to press the red SOS button on their dashboard immediately.
Never give medical prescriptions or legal advice; only provide validation and resource suggestions.";

if ($isSilent) {
    $db = new db_conn();
    $sql = "INSERT INTO ai_logs (user_id, user_message, ai_response, sentiment_flag) VALUES (?, ?, ?, ?)";
    $db->db_query($sql, [$user_id, $userMessage, 'SILENT_VENT', 'NEUTRAL']);
    
    echo json_encode(['status' => 'logged_silently']);
    exit;
}

$data = [
    'model' => 'gpt-3.5-turbo', // or gpt-4
    'messages' => [
        ['role' => 'system', 'content' => $systemPrompt],
        ['role' => 'user', 'content' => $userMessage]
    ],
    'temperature' => 0.7
];

// Send request to OpenAI via CURL
$ch = curl_init('https://api.openai.com/v1/chat/completions');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $apiKey
]);

$response = curl_exec($ch);
$err = curl_error($ch);
curl_close($ch);

// Log interaction for Admin Review (Project requirement)
// $db->query("INSERT INTO ai_logs (user_id, message, ai_response) VALUES (?, ?, ?)", [...]);
if ($err) {
    echo json_encode(['error' => 'CURL Error: ' . $err]);
} else {
    // Decode response to save to DB
    $resData = json_decode($response, true);

    // CHECK FOR ERRORS
    if ($httpCode !== 200) {
        // If OpenAI sends an error (like "Insufficient Quota"), show it in the console
        echo json_encode([
            'reply' => 'My connection to the support server is currently restricted.',
            'debug_error' => $resData['error']['message'] ?? 'Unknown API Error',
            'http_code' => $httpCode
        ]);
        exit;
    }

    $aiReply = $resData['choices'][0]['message']['content'] ?? 'I am listening.';
    // LOG TO DATABASE
    $db = new db_conn();
    $sql = "INSERT INTO ai_logs (user_id, user_message, ai_response, sentiment_flag) VALUES (?, ?, ?, ?)";
    $db->db_query($sql, [$user_id, $userMessage, $aiReply, 'NEUTRAL']);

    // Send back to JS
    echo json_encode(['reply' => $aiReply]);
}