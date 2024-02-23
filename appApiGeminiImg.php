<?php
function getGeminiResponse($apiKey, $inputText, $imagePath, $temperature, $maxTokens, $proxyAddress, $proxyPort, $proxyUser, $proxyPass) {
    $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-pro-vision:generateContent?key=" . $apiKey;

    $headers = [
        'Content-Type: application/json'
    ];

    // Кодируем изображение в Base64
    $imageData = base64_encode(file_get_contents($imagePath));

    $data = [
        'contents' => [
            [
                'parts' => [
                    ['text' => $inputText],
                    [
                        'inline_data' => [
                            'mime_type' => 'image/jpeg',
                            'data' => $imageData
                        ]
                    ]
                ]
            ]
        ],
        'generationConfig' => [
            'temperature' => $temperature,
            'maxOutputTokens' => $maxTokens
        ]
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    // Настройка прокси с аутентификацией
    curl_setopt($ch, CURLOPT_PROXY, $proxyAddress);
    curl_setopt($ch, CURLOPT_PROXYPORT, $proxyPort);
    curl_setopt($ch, CURLOPT_PROXYUSERPWD, "$proxyUser:$proxyPass");

    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        throw new Exception(curl_error($ch));
    }
    curl_close($ch);

    return $response;
}

// Пример использования функции
$apiKey = 'AIzaSyBZMejQzzH1Z1cVYp6FNxUIWmQ9OcVL5jg'; // Твой API ключ
$inputText = "Что на фото? Напиши модели"; // Текст запроса
$imagePath = "https://sun9-62.userapi.com/impg/UwOusZ5Jd9mRWya3Zqoty6ubYAHZFAu3RRjMSA/_3GzKc_HIX4.jpg?size=1575x2100&quality=95&sign=50446870cfbc634a09044112681373a9&type=album"; // Путь к файлу изображения
$temperature = 1; // Температура для генерации
$maxTokens = 1500; // Максимальное количество токенов
$proxyAddress = "45.128.156.22"; // Адрес прокси-сервера
$proxyPort = "13828"; // Порт прокси-сервера
$proxyUser = "modeler_641r99"; // Имя пользователя прокси
$proxyPass = "QyagcxaPkuaN"; // Пароль прокси

try {
    $response = getGeminiResponse($apiKey, $inputText, $imagePath, $temperature, $maxTokens, $proxyAddress, $proxyPort, $proxyUser, $proxyPass);

    // Вывод ответа в формате JSON
    header('Content-Type: application/json');
    echo $response;
} catch (Exception $e) {
    // В случае ошибки выводим сообщение об ошибке
    header('HTTP/1.1 500 Internal Server Error');
    echo json_encode(['error' => $e->getMessage()]);
}
?>

