<?php
function getGeminiResponse($apiKey, $inputText, $proxyAddress, $proxyPort, $proxyUser, $proxyPass) {
    $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key=" . $apiKey;

    $headers = [
        'Content-Type: application/json'
    ];

    $data = [
        'contents' => [
            [
                'parts' => [
                    ['text' => $inputText]
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
    curl_setopt($ch, CURLOPT_PROXYUSERPWD, "$proxyUser:$proxyPass"); // Формат "username:password"

    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        throw new Exception(curl_error($ch));
    }
    curl_close($ch);

    return $response;
}

// Пример использования функции
$apiKey = 'AIzaSyBZMejQzzH1Z1cVYp6FNxUIWmQ9OcVL5jg'; // Замени это на свой действительный API ключ
$inputText = "Опиши комичную ситуацию, сделай это креативно и с сатирой в гоголевском стиле. Придумай захватывающую концовку"; // "Напиши короткий интересный научный факт"; // Пример текста для генерации
$temperature = 1; // Задаем температуру
$maxTokens = 1500; // Устанавливаем максимальное количество токенов
$proxyAddress = "45.128.156.22"; // Адрес прокси-сервера
$proxyPort = "13828"; // Порт прокси-сервера
$proxyUser = "modeler_641r99"; // Имя пользователя прокси
$proxyPass = "QyagcxaPkuaN"; // Пароль прокси

try {
    $response = getGeminiResponse($apiKey, $inputText, $proxyAddress, $proxyPort, $proxyUser, $proxyPass);

    // Выводим ответ в формате JSON
    header('Content-Type: application/json');
    echo $response;
} catch (Exception $e) {
    // В случае ошибки выводим сообщение об ошибке
    header('HTTP/1.1 500 Internal Server Error');
    echo json_encode(['error' => $e->getMessage()]);
}


?>
