<?php

namespace App\Http\Controllers;

use App\Http\Requests\getAmountCharactersByLocationIDRequest;
use App\Http\Requests\getCharactersByEpisodeIDRequest;
use App\Http\Requests\getLocationRequest;

class ApiController extends Controller
{
    public function getLocation(getLocationRequest $request)
    {
        $url = 'https://rickandmortyapi.com/api/location/';
        $params = $request->input('array');
        if (!empty($params)) {
            $url .= implode(',', $params);
        }
        return response(['data' => ApiHelper::sendRequest($url)]);
    }

    public function getAmountCharactersByLocationID(getAmountCharactersByLocationIDRequest $request)
    {
        $url = 'https://rickandmortyapi.com/api/location/' . $request->input('location_id');
        $response = ApiHelper::sendRequest($url);
        return response(['amount' => count($response->residents)], 200);
    }

    public function getCharactersByEpisodeID(getCharactersByEpisodeIDRequest $request)
    {
        $url = 'https://rickandmortyapi.com/api/episode/' . $request->input('episode_id');
        $episode = ApiHelper::sendRequest($url);
        //Ну тут как бы 2 стула
        // Или бегать постоянно по каждому characters к api что порождает много запросов и это не есть хорошо наверное
        // Или сформировать массив id и сбегать по api 1 раз
        foreach ($episode->characters as &$item) {
            $item = parse_url($item);
            $item['path'] = explode('/', $item['path']);
            $characters_ids[] = end($item['path']);
        }
        $url = 'https://rickandmortyapi.com/api/character/' . implode(',', $characters_ids);
        $characters = ApiHelper::sendRequest($url);
        $episode->characters = $characters;
        $response = $episode;
        return response(['data' => $response], 200);
    }

    //
}
