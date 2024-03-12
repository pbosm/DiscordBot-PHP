<?php

namespace Pablo\Botdiscordphp;

class WeatherChecker {
    private $apiKey = 'SUA_KEY';

    public function getWeather($city) {
        $url = "http://api.openweathermap.org/data/2.5/weather?q={$city}&appid={$this->apiKey}&units=metric";
        $weatherData = $this->fetchData($url);

        if ($weatherData) {
            return [
                'temperature' => $weatherData['main']['temp'],
                'description' => $weatherData['weather'][0]['description'],
                'humidity' => $weatherData['main']['humidity'],
                'wind_speed' => $weatherData['wind']['speed'],
                'country' => $weatherData['sys']['country'],
                'city' => $weatherData['name']
            ];
        } else {
            return false;
        }
    }

    private function fetchData($url) {
        $response = file_get_contents($url);
        
        return json_decode($response, true);
    }
}

?>
