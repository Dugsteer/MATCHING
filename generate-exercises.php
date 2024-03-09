<?php
ob_start();
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Assuming words are sent in a single string, comma-separated
    $englishWords = isset($_POST['words']) ? explode(',', $_POST['words']) : [];
    $promptContent = "Translate the following English words into Spanish: " . implode(', ', $englishWords) . ".";

    // OpenAI Chat API URL
    $url = 'https://api.openai.com/v1/chat/completions';

    // OpenAI API Key
    $apiKey = 'your API key here';

    // Prepare the messages
    $messages = [
        ["role" => "system", "content" => "You are a helpful assistant capable of translating English words to Spanish."],
        ["role" => "user", "content" => $promptContent]
    ];

    // Prepare the data
    $data = [
        "model" => "gpt-3.5-turbo",
        "messages" => $messages,
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $apiKey,
    ]);

    $response = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if (curl_errno($ch)) {
        error_log('cURL error: ' . curl_error($ch));
    } else if ($httpcode != 200) {
        error_log("OpenAI API request failed with response code {$httpcode} and body: {$response}");
    }

    curl_close($ch);

    // Decode the response
    $responseDecoded = json_decode($response, true);
    if (isset($responseDecoded['choices'][0]['message']['content'])) {
        $translationResponse = $responseDecoded['choices'][0]['message']['content'];
    } else {
        $translationResponse = "Translation not found.";
    }

    ob_clean();
    header('Content-Type: application/json');
    echo json_encode([
        'english_words' => $englishWords,
        'spanish_words' => [$translationResponse], // Encapsulate in array for consistency
    ]);
    exit;
}
?>
