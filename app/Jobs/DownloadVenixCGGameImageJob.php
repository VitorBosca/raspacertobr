<?php

namespace App\Jobs;

use App\Models\Game;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DownloadVenixCGGameImageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $url;
    protected $name;

    /**
     * Create a new job instance.
     */
    public function __construct($url, $name = null)
    {
        $this->url = $url;
        $this->name = $name;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        try {
            $response = Http::get($this->url);

            if ($response->ok()) {
                $fileContent = $response->body();

                $parsedUrl = parse_url($this->url);
                $pathInfo = pathinfo($parsedUrl['path']);
                $fileName = $this->name ?? $pathInfo['filename'];
                $extension = $pathInfo['extension'] ?? 'png'; // Extensão do arquivo

                $fileName = 'venixcg/' . $fileName . '.' . $extension;
                Storage::disk('public')->put($fileName, $fileContent);

                if ($this->name) {
                    $game = Game::where([
                        ['game_code', '=', $this->name],
                        ['distribution', '=', 'venixcg']
                    ])->first();
                    if ($game) {
                        $game->update(['cover' => $fileName]);
                    }
                }

                return $fileName;
            }
        } catch (\Exception $e) {
            return null;
        }
    }
}
