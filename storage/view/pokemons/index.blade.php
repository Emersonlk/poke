<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pokemon</title>

    <style>
        .container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .pokemon-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }

        .name {
            margin: 0;
            font-weight: bold;
            text-transform: uppercase;
        }

        .moves {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .moves-title {
            font-weight: bold;
            margin: 0;
            text-transform: capitalize;
        }

        .card {
            max-width: 360px;
            background-color: red;
            padding: 20px;
            border-radius: 10px;
            display: flex;
            flex-direction: column;
        }

        .card:nth-child(5n+1) {
            background-color: #b2f2bb;
        }

        .card:nth-child(5n+2) {
            background-color: #ffec99;
        }

        .card:nth-child(5n+3) {
            background-color: #ff0000;
        }

        .card:nth-child(5n+4) {
            background-color: #ffd8a8;
        }

        .card:nth-child(5n+5) {
            background-color: #a5d8ff;
        }

        .thumbnail {
            width: 100%;
            max-width: 200px;
            margin: 0 auto;
        }

        .inner-card {
            display: flex;
            flex-direction: column;
            gap: 15px;
            border-radius: 10px;
            border: 1px solid black;
            padding: 10px;
            height: 100%;
        }

        #submit_button {
            background-color: #ffc9c9;
            border: 1px solid black;
            border-radius: 15px;
            margin: 0 auto;
            margin-bottom: 40px;
            padding: 20px 40px;
            cursor: pointer;
            display: block;
            box-shadow: 3px 3px 5px 0px rgba(0, 0, 0, 0.7);
        }
    </style>

</head>

<body>
    <div class="container">

        <form action="/" method="GET">
            <button type="submit" id="submit_button">Sortear</button>
            <input type="hidden" name="search" value="true">
        </form>

        @if ($search)
        <div class="pokemon-list">

            @foreach ($pokemons as $pokemon)
            <div class="card">

                <h2 class="name">
                    {{$pokemon['name']}}
                </h2>

                <img class="thumbnail" src="{{$pokemon['sprites']['front_default']}}" alt="">

                <div class="inner-card">

                    @foreach ($pokemon['moves_with_effects'] as $move)

                    <div class="moves">
                        <h3 class="moves-title">
                            {{$move['name']}}
                        </h3>
                        <div class="moves-description">
                            {{$move['effect']}}
                        </div>
                    </div>

                    @endforeach

                </div>

            </div>
            @endforeach

        </div>
        @endif
    </div>
</body>

</html>