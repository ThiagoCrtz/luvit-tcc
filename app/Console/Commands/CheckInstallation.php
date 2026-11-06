<?php

namespace App\Console\Commands;

use App\Services\RawgService;
use App\Services\TmdbClient;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CheckInstallation extends Command
{
    protected $signature = 'luvit:check {--catalogues : Consultar também os catálogos externos}';
    protected $description = 'Verifica banco, arquivos, configuração e acesso aos catálogos sem exibir credenciais.';

    public function handle(TmdbClient $tmdb, RawgService $rawg): int
    {
        $checks = [];
        try {
            DB::connection()->getPdo();
            $checks['Banco de dados'] = Schema::hasTable('table_users') && Schema::hasTable('table_reviews');
        } catch (\Throwable $e) {
            $checks['Banco de dados'] = false;
        }
        $checks['Chave da aplicação'] = filled(config('app.key'));
        $checks['Interface compilada'] = file_exists(public_path('build/manifest.json'));
        $checks['Fotos públicas'] = is_dir(public_path('storage'));
        $checks['TMDB configurado'] = filled(config('services.tmdb.key'));
        $checks['RAWG configurado'] = filled(config('services.rawg.key'));
        if ($this->option('catalogues')) {
            $checks['Catálogo de filmes'] = count($tmdb->popular()['results'] ?? []) > 0;
            $checks['Catálogo de jogos'] = count($rawg->getPopularGames()['results'] ?? []) > 0;
        }
        foreach ($checks as $label => $ok) {
            $this->line(($ok ? '[OK] ' : '[FALHA] ') . $label);
        }
        $this->line(config('services.google.client_id') && config('services.google.client_secret')
            ? '[OK] Login Google configurado (validar callback no navegador).'
            : '[PENDENTE] Login Google: preencher GOOGLE_CLIENT_ID e GOOGLE_CLIENT_SECRET.');

        return in_array(false, $checks, true) ? self::FAILURE : self::SUCCESS;
    }
}
