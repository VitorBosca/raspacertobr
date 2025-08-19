<?php

namespace App\Http\Controllers\Api\Games;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\GameFavorite;
use App\Models\GameLike;
use App\Models\GamesKey;
use App\Models\Gateway;
use App\Models\Provider;
use App\Models\Wallet;
use App\Traits\Providers\EvergameTrait;
use App\Traits\Providers\FiversTrait;
use App\Traits\Providers\Games2ApiTrait;
use App\Traits\Providers\PlayGamingTrait;
use App\Traits\Providers\SalsaGamesTrait;
use App\Traits\Providers\VenixCGTrait;
use App\Traits\Providers\VeniXTrait;
use App\Traits\Providers\VibraTrait;
use App\Traits\Providers\WorldSlotTrait;
use Illuminate\Http\Request;

class GameController extends Controller
{
    use FiversTrait,
        VibraTrait,
        SalsaGamesTrait,
        WorldSlotTrait,
        Games2ApiTrait,
        VeniXTrait,
        EvergameTrait,
        PlayGamingTrait,
        VenixCGTrait;

    /**
     * @dev venixplataformas
     * Display a listing of the resource.
     */
    public function index()
    {
        $providers = Provider::with(['games', 'games.provider'])
            ->whereHas('games')
            ->orderBy('name', 'desc')
            ->where('status', 1)
            ->get();

        return response()->json(['providers' =>$providers]);
    }

    /**
     * @dev venixplataformas
     * @return \Illuminate\Http\JsonResponse
     */
    public function featured()
    {
        $featured_games = Game::with(['provider'])
                    ->where('is_featured', 1)
                    ->where('status', 1)
                    ->get();

        return response()->json(['featured_games' => $featured_games]);
    }

    /**
     * Source Provider
     *
     * @dev venixplataformas
     * @param Request $request
     * @param $token
     * @param $action
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function sourceProvider(Request $request, $token, $action)
    {
        $tokenOpen = \Helper::DecToken($token);
        $validEndpoints = ['session', 'icons', 'spin', 'freenum'];

        if (in_array($action, $validEndpoints)) {
            if(isset($tokenOpen['status']) && $tokenOpen['status'])
            {
                $game = Game::whereStatus(1)->where('game_code', $tokenOpen['game'])->first();
                if(!empty($game)) {
                    $controller = \Helper::createController($game->game_code);

                    switch ($action) {
                        case 'session':
                            return $controller->session($token);
                        case 'spin':
                            return $controller->spin($request, $token);
                        case 'freenum':
                            return $controller->freenum($request, $token);
                        case 'icons':
                            return $controller->icons();
                    }
                }
            }
        } else {
            return response()->json([], 500);
        }
    }

    /**
     * @dev venixplataformas
     * Store a newly created resource in storage.
     */
    public function toggleFavorite($id)
    {
        if(auth('api')->check()) {
            $checkExist = GameFavorite::where('user_id', auth('api')->id())->where('game_id', $id)->first();
            if(!empty($checkExist)) {
                if($checkExist->delete()) {
                    return response()->json(['status' => true, 'message' => 'Removido com sucesso']);
                }
            }else{
                $gameFavoriteCreate = GameFavorite::create([
                    'user_id' => auth('api')->id(),
                    'game_id' => $id
                ]);

                if($gameFavoriteCreate) {
                    return response()->json(['status' => true, 'message' => 'Criado com sucesso']);
                }
            }
        }
    }

    /**
     * @dev venixplataformas
     * Store a newly created resource in storage.
     */
    public function toggleLike($id)
    {
        if(auth('api')->check()) {
            $checkExist = GameLike::where('user_id', auth('api')->id())->where('game_id', $id)->first();
            if(!empty($checkExist)) {
                if($checkExist->delete()) {
                    return response()->json(['status' => true, 'message' => 'Removido com sucesso']);
                }
            }else{
                $gameLikeCreate = GameLike::create([
                    'user_id' => auth('api')->id(),
                    'game_id' => $id
                ]);

                if($gameLikeCreate) {
                    return response()->json(['status' => true, 'message' => 'Criado com sucesso']);
                }
            }
        }
    }

    /**
     * @dev venixplataformas
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $game = Game::select(['game_name', 'valor'])->where('id', $id)->first();
        if ($game) {
            return response()->json($game);
        }
        return response()->json(['message' => 'Game not found'], 404);
    }

    /**
     * @dev venixplataformas
     * Show the form for editing the specified resource.
     */
    public function allGames(Request $request)
    {
        $query = Game::query();
        $query->with(['provider', 'categories']);

        if (!empty($request->provider) && $request->provider != 'all') {
            $query->where('provider_id', $request->provider);
        }

        if (!empty($request->category) && $request->category != 'all') {
            $query->whereHas('categories', function ($categoryQuery) use ($request) {
                $categoryQuery->where('slug', $request->category);
            });
        }

        if (isset($request->searchTerm) && !empty($request->searchTerm) && strlen($request->searchTerm) > 2) {
            $query->whereLike(['game_code', 'game_name', 'description', 'distribution', 'provider.name'], $request->searchTerm);
        }else{
            $query->orderBy('views', 'desc');
        }

        $games = $query
            ->where('status', 1)
            ->paginate(12)->appends(request()->query());

        return response()->json(['games' => $games]);
    }

    /**
     * @dev venixplataformas
     * Update the specified resource in storage.
     */
    public function webhookGoldApiMethod(Request $request)
    {
        return self::WebhooksFivers($request);
    }

    /**
     * @dev venixplataformas
     * Update the specified resource in storage.
     */
    public function webhookUserBalanceMethod(Request $request)
    {
        return self::GetUserBalanceWorldSlot($request);
    }

    /**
     * @dev venixplataformas
     * Update the specified resource in storage.
     */
    public function webhookGameCallbackMethod(Request $request)
    {
        return self::GameCallbackWorldSlot($request);
    }

    /**
     * @dev venixplataformas
     * Update the specified resource in storage.
     */
    public function webhookMoneyCallbackMethod(Request $request)
    {
        return self::MoneyCallbackWorldSlot($request);
    }

    /**
     * Webhook Vibra Method
     *
     * @param Request $request
     * @param $parameters
     * @return array|\Illuminate\Http\JsonResponse|null
     */
    public function webhookVibraMethod(Request $request, $parameters)
    {
        return self::WebhookVibra($request, $parameters);
    }

    /**
     * @param Request $request
     * @return null
     */
    public function webhookKaGamingMethod(Request $request)
    {
        return self::WebhookKaGaming($request);
    }

    /**
     * @param Request $request
     * @return null
     */
    public function webhookSalsaMethod(Request $request)
    {
        return self::webhookSalsa($request);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function webhookVeniXMethod(Request $request)
    {
        return self::WebhookVenixCG($request);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|null
     */
    public function webhookEvergameMethod(Request $request)
    {
        return self::WebhooksEvergame($request);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|null
     */
    public function webhookPlayGamingMethod(Request $request)
    {
        return self::WebhooksPlayGaming($request);
    }
}
