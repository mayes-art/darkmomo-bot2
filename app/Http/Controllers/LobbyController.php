<?php

namespace App\Http\Controllers;

use App\Constants\Constellation;
use App\Services\LineBotService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use LINE\LINEBot;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot\MessageBuilder\MultiMessageBuilder;

class LobbyController extends Controller
{

    const HOME_GROUP_ID = ['C941e3d40dc9046bd0d4308224a086b29'];

    protected $bot;
    protected $messageBuilder;
    protected $lineBotService;

    public function __construct(LineBotService $lineBotService)
    {
        $httpClient = new CurlHTTPClient(env('LINE_BOT_CHANNEL_ACCESS_TOKEN'));
        $this->bot = new LINEBot($httpClient, ['channelSecret' => env('LINE_BOT_CHANNEL_SECRET')]);
        $this->messageBuilder = new MultiMessageBuilder();
        $this->lineBotService = $lineBotService;
    }

    public function lineGet()
    {
        try {
            $event = request()->all();
            Log::info(json_encode($event, JSON_UNESCAPED_UNICODE));
            return response('test');
        } catch (\Exception $e) {
            report($e);
        }
    }

    public function linePost(Request $request)
    {
        try {
            $event = $request->all();
            Log::info(json_encode($event, JSON_UNESCAPED_UNICODE));

            $this->lineBotService->setBot($event);
            if ('text' !== $this->lineBotService->getReqType()) {
                return;
            }

            $say = $this->lineBotService->getSay();

            if (Str::contains($say, '看韓劇')) {
                $geCode = 20;
                $stringFormat = explode(' ', $say);
                $wd = urlencode($stringFormat[1]);
//                $tvUrl = "https://gimy.tv/s/-------------.html?wd={$wd}&submit=";
                $tvUrl = "https://gimy.tv/genre/{$geCode}-----------.html?wd={$wd}&submit=";
                $this->lineBotService->setText($tvUrl);
            }

            if (Str::contains($say, '看美劇')) {
                $geCode = 16;
                $stringFormat = explode(' ', $say);
                $wd = urlencode($stringFormat[1]);
                $tvUrl = "https://gimy.tv/genre/{$geCode}-----------.html?wd={$wd}&submit=";
                $this->lineBotService->setText($tvUrl);
            }

            if (Str::contains($say, '看日劇')) {
                $geCode = 15;
                $stringFormat = explode(' ', $say);
                $wd = urlencode($stringFormat[1]);
                $tvUrl = "https://gimy.tv/genre/{$geCode}-----------.html?wd={$wd}&submit=";
                $this->lineBotService->setText($tvUrl);
            }

            if (Str::contains($say, '抽美女')) {
                $imgurImages = 'https://api.imgur.com/3/album/bGVWzR2/images';
                $accessToken = '23a3fc911a3e85e0111de632b42d39e0e6bc1551';
                $response = Http::withToken($accessToken)->get($imgurImages);
                if ($response->successful()) {
                    $image = collect($response->json('data'))->random();
                    $this->lineBotService->setImage($image['link']);
                }
            }

            if (Str::contains($say, '抽帥哥')) {
                $imgurImages = 'https://api.imgur.com/3/album/RBT5Tl8/images';
                $accessToken = '23a3fc911a3e85e0111de632b42d39e0e6bc1551';
                $response = Http::withToken($accessToken)->get($imgurImages);
                if ($response->successful()) {
                    $image = collect($response->json('data'))->random();
                    $this->lineBotService->setImage($image['link']);
                }
            }

//            if (Str::contains("{$say}座", Constellation::ALL_TW)) {
//                if ('牡羊' == $say) {
//                    $say = '白羊';
//                }
//
//                $say2s = Chinese::simplified($say . "座");
//                $apiUri = "http://crys.top/api/conste.php?msg={$say2s}";
//                $response = Http::get($apiUri);
//                if ($response->successful()) {
//                    $this->lineBotService->setText(Chinese::traditional($response->body()));
//                }
//            }



            if (Str::contains($say, ['roll', 'Roll', 'ROLL'])) {
                $message = '(1~100)隨機骰出來的數字為: ' . $this->lineBotService->randomChange();
                $this->lineBotService->setText($message);
            }

//            if ('text' == $this->lineBotService->getReqType() && $this->lineBotService->randomChange() <= 34) {
//                $this->lineBotService->setText('嘔咾上帝, 阿們');
//            }

            $this->lineBotService->reply();
        } catch (\Exception $e) {
            report($e);
        }
    }

    public function test(Request $request)
    {
        try {
            $event = $request->all();
            $this->lineBotService->setBot($event);
            $say = $this->lineBotService->getSay();

            $data = file_get_contents(storage_path('app/public/pwd_game.json'));

            echo $data;
        } catch (\Exception $e) {
            report($e);
        }
    }

    public function testReply()
    {
        try {
            $message = 'Hello World';
            $textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($message);
            $response = $this->bot->replyMessage(env(), $textMessageBuilder);
            if ($response->isSucceeded()) {
                echo 'Succeeded!';
                return;
            }

            echo $response->getHTTPStatus() . ' ' . $response->getRawBody();
        } catch (\Exception $e) {
            report($e);
        }
    }
}
