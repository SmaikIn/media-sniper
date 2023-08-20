<?php

namespace App\Http\Controllers;

class ApiHelper extends Controller
{
    public static function sendRequest($url)
    {
        try {
            // Создаем новый cURL ресурс
            $ch = curl_init();

            // Устанавливаем URL и другие необходимые опции
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Accept: application/json'
            ));
            // Отправляем запрос и получаем ответ
            $response = curl_exec($ch);
            // Проверяем наличие ошибок
            if(curl_errno($ch)){
                throw new \Exception(curl_error($ch));
            }

            // Закрываем cURL ресурс
            curl_close($ch);

            // Возвращаем ответ
            return json_decode($response);
        } catch (\Exception $e) {
            // Обрабатываем ошибку
            // Возвращаем ошибку в формате JSON
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    //
}
