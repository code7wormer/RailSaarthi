<?php
require_once 'api_config.php';

/**
 * Fetch data from Indian Railway IRCTC API (RapidAPI)
 * 
 * @param string $url The full API endpoint URL
 * @param array $queryParams Query parameters
 * @return array
 */
function fetchRailwayData($url, $queryParams = []) {
    $fullUrl = $url;
    if (!empty($queryParams)) {
        $fullUrl .= (strpos($url, '?') === false ? '?' : '&') . http_build_query($queryParams);
    }

    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => $fullUrl,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "x-rapidapi-host: " . RAPIDAPI_HOST,
            "x-rapidapi-key: " . RAPIDAPI_KEY,
            "x-rapid-api: " . RAPIDAPI_DB,
            "Content-Type: application/json"
        ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    curl_close($curl);

    $debug_info = [
        "url" => $fullUrl,
        "httpCode" => $httpCode,
        "response" => json_decode($response, true) ?: $response,
        "error" => $err
    ];

    // Debugging to Console
    echo "<script>console.log('API Debug [RapidAPI]:', " . json_encode($debug_info, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE) . ");</script>";

    if ($err) {
        return ["error" => "Communication Error: " . $err];
    }

    if ($httpCode >= 400) {
        $errorMsg = "API Error (HTTP $httpCode)";
        $details = json_decode($response, true);
        if (isset($details['message'])) {
            $errorMsg .= ": " . $details['message'];
        }
        return ["error" => $errorMsg, "http_code" => $httpCode];
    }

    $result = json_decode($response, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        return ["error" => "Invalid response from server", "raw" => $response];
    }

    return $result;
}
?>
