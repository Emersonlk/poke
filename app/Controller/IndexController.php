<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace App\Controller;

use Hyperf\HttpServer\Contract\RequestInterface;
use function Hyperf\Collection\collect;
use function Hyperf\ViewEngine\view;
use function Hyperf\Coroutine\co;
use function Hyperf\Support\env;

class IndexController extends AbstractController
{
    public function index(RequestInterface $request)
    {
        require 'vendor/autoload.php';

        $client = new \GuzzleHttp\Client();
        $wg = new \Hyperf\Coroutine\WaitGroup();
        $pokemons = [];

        // Gera IDs aleatórios dos Pokémons
        $pokemonIds = $this->getRandomPokemonIds();

        foreach ($pokemonIds as $id) {
            $wg->add(1);

            co(function () use ($wg, $client, &$pokemons, $id) {
                $response = $client->get(env('API_URL') . "{$id}");
                $body = (string) $response->getBody();
                $pokemonData = json_decode($body, true);

                $movesWithEffects = [];
                $moveWg = new \Hyperf\Coroutine\WaitGroup();

                // Separei apenas 5 movimentos se não ficaria enorme os movimentos na view
                foreach (array_slice($pokemonData['moves'], 0, 5) as $move) {
                    $moveWg->add(1);

                    co(function () use ($moveWg, $client, &$movesWithEffects, $move) {
                        $moveResponse = $client->get($move['move']['url']);
                        $moveData = json_decode((string)$moveResponse->getBody(), true);

                        $movesWithEffects[] = [
                            'name' => $move['move']['name'],
                            'effect' => $moveData['effect_entries'][0]['effect'] ?? 'No effect information available',
                        ];

                        $moveWg->done();
                    });
                }

                $moveWg->wait();

                $pokemonData['moves_with_effects'] = $movesWithEffects;
                $pokemons[] = $pokemonData;

                $wg->done();
            });
        }

        $wg->wait();

        return view('pokemons.index', [
            'pokemons' => collect($pokemons),
            'search'   => $request->input('search'),
        ]);
    }

    // Pega Ids Aleatorios de pokemons
    public function getRandomPokemonIds($count = 5, $max = 1010)
    {
        $ids = [];
        while (count($ids) < $count) {
            $id = rand(1, $max);
            if (!in_array($id, $ids)) {
                $ids[] = $id;
            }
        }
        return $ids;
    }
}
