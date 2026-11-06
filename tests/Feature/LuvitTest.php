<?php

namespace Tests\Feature;

use App\Models\Game;
use App\Models\ListModel;
use App\Models\Movie;
use App\Models\Review;
use App\Models\User;
use App\Services\IgdbService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LuvitTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['services.tmdb.key' => 'test', 'services.rawg.key' => 'test',
            'services.igdb.client_id' => null, 'services.igdb.client_secret' => null]);
        Http::preventStrayRequests();
        $movie = [
            'id' => 550, 'title' => 'Filme de teste', 'overview' => 'Sinopse de teste.',
            'poster_path' => '/poster.jpg', 'backdrop_path' => '/backdrop.jpg',
            'release_date' => '2020-01-01', 'vote_average' => 8.5, 'vote_count' => 100,
            'runtime' => 120, 'original_language' => 'en', 'imdb_id' => 'tt0000001',
            'genres' => [['id' => 18, 'name' => 'Drama']], 'genre_ids' => [18],
            'credits' => ['cast' => [], 'crew' => []], 'videos' => ['results' => []],
        ];
        $game = [
            'id' => 3498, 'name' => 'Jogo de teste', 'slug' => 'jogo-de-teste',
            'background_image' => 'https://example.com/game.jpg', 'released' => '2020-01-01',
            'rating' => 4.5, 'metacritic' => 90, 'playtime' => 20,
            'description_raw' => 'Descrição de teste.', 'description' => '<p>Descrição de teste.</p>',
            'genres' => [['id' => 4, 'name' => 'Action']], 'platforms' => [],
            'developers' => [], 'publishers' => [], 'tags' => [],
        ];
        Http::fake([
            'api.themoviedb.org/3/movie/550*' => Http::response($movie),
            'api.themoviedb.org/3/genre/movie/list*' => Http::response(['genres' => $movie['genres']]),
            'api.themoviedb.org/*' => Http::response(['results' => [$movie]]),
            'api.rawg.io/api/games/3498*' => Http::response($game),
            'api.rawg.io/*' => Http::response(['results' => [$game]]),
        ]);
    }

    public function test_public_pages_and_catalogue_details_render(): void
    {
        foreach (['/', '/login', '/register', '/filmes', '/games', '/filme/550', '/games/3498'] as $url) {
            $this->get($url)->assertOk();
        }
        $this->assertDatabaseHas('table_movies', ['tmdb_id' => 550]);
        $this->assertDatabaseHas('table_games', ['rawg_id' => 3498]);
    }

    public function test_registration_login_logout_and_guest_redirect(): void
    {
        $this->get('/lists')->assertRedirect('/login');
        $this->post('/register', [
            'name' => 'Pessoa Teste', 'arroba' => 'pessoateste', 'email' => 'pessoa@example.com',
            'password' => 'senha1234', 'password_confirmation' => 'senha1234',
        ])->assertRedirect('/home');
        $this->assertAuthenticated();
        $this->get('/home')->assertOk();
        $this->get('/')->assertRedirect('/home');
        $this->post('/logout')->assertRedirect('/');
        $this->assertGuest();
        $this->post('/login', ['email' => 'pessoa@example.com', 'password' => 'errada'])->assertSessionHasErrors('email');
        $this->post('/login', ['email' => 'pessoa@example.com', 'password' => 'senha1234'])->assertRedirect('/home');
        $this->assertAuthenticated();
    }

    public function test_movie_and_game_likes_watchlists_and_reviews_persist(): void
    {
        $this->actingAs(User::factory()->create());
        foreach (['filme/550', 'game/3498'] as $prefix) {
            $this->postJson("/$prefix/like")->assertOk();
            $this->assertDatabaseCount('table_likes', 1);
            $this->postJson("/$prefix/like")->assertOk();
            $this->assertDatabaseCount('table_likes', 0);
            $this->postJson("/$prefix/watchlist")->assertOk()->assertJson(['added' => true]);
            $this->assertDatabaseCount('table_watchlist', 1);
            $this->postJson("/$prefix/watchlist")->assertOk()->assertJson(['added' => false]);
            $this->assertDatabaseCount('table_watchlist', 0);
            $this->post("/$prefix/review", ['review' => 'Gostei deste título!', 'rating' => 5])->assertRedirect();
            $review = Review::latest('id')->first();
            $this->post("/$prefix/review/{$review->id}/reply", ['review' => 'Concordo!'])->assertRedirect();
        }
        $this->assertDatabaseCount('table_reviews', 4);
        $this->get('/profile/' . auth()->user()->arroba)->assertOk();
    }

    public function test_replies_cannot_reference_another_title(): void
    {
        $this->actingAs($user = User::factory()->create());
        $movie = Movie::create(['tmdb_id' => 1, 'title' => 'Outro filme']);
        $review = Review::create(['user_id' => $user->id, 'movie_id' => $movie->id, 'review' => 'Outro comentário']);
        $this->postJson("/filme/550/review/{$review->id}/reply", ['review' => 'Resposta inválida'])->assertUnprocessable();
        $this->assertDatabaseCount('table_reviews', 1);
    }

    public function test_lists_are_public_but_only_the_owner_can_change_them(): void
    {
        $this->actingAs($owner = User::factory()->create());
        $this->post('/lists', ['name' => 'Minha lista', 'media' => [json_encode([
            'media_type' => 'movie', 'media_id' => 550, 'title' => 'Filme de teste',
        ])]])->assertRedirect('/lists');
        $list = ListModel::firstOrFail();
        $this->get('/lists')->assertOk();
        $this->get('/lists/create')->assertOk();
        $this->get("/lists/{$list->id}")->assertOk();
        $item = ['media_type' => 'game', 'media_id' => 3498, 'title' => 'Jogo de teste'];
        $this->postJson("/lists/{$list->id}/add", $item)->assertOk();
        $this->postJson("/lists/{$list->id}/add", $item)->assertStatus(400);
        $this->actingAs(User::factory()->create());
        $this->postJson("/lists/{$list->id}/add", $item)->assertNotFound();
        $this->delete("/lists/{$list->id}")->assertNotFound();
        auth()->logout();
        $this->get("/list/{$list->id}")->assertOk();
        $this->get("/@{$owner->arroba}/lists")->assertOk();
        $this->actingAs($owner);
        $this->delete("/lists/{$list->id}/items/" . $list->items()->first()->id)->assertRedirect();
        $this->delete("/lists/{$list->id}")->assertRedirect('/lists');
        $this->assertDatabaseCount('table_list_items', 0);
    }

    public function test_following_and_profile_authorization(): void
    {
        $this->actingAs($me = User::factory()->create());
        $other = User::factory()->create();
        $this->postJson("/profile/{$other->arroba}/follow")->assertOk()->assertJson(['isFollowing' => true]);
        $this->get("/profile/{$other->arroba}/followers")->assertOk();
        $this->get("/profile/{$me->arroba}/following")->assertOk();
        $this->get("/profile/{$other->arroba}/edit")->assertForbidden();
        $this->put("/profile/{$other->arroba}", ['name' => 'Inválido'])->assertForbidden();
        $this->get("/profile/{$me->arroba}/edit")->assertOk();
        $this->put("/profile/{$me->arroba}", ['name' => 'Nome novo', 'bio' => 'Minha bio'])->assertRedirect();
        $this->assertDatabaseHas('table_users', ['id' => $me->id, 'name' => 'Nome novo']);
        $this->postJson("/profile/{$other->arroba}/follow")->assertOk()->assertJson(['isFollowing' => false]);
        $this->postJson("/profile/{$me->arroba}/follow")->assertStatus(400);
    }

    public function test_search_returns_movies_games_and_users(): void
    {
        User::factory()->create(['name' => 'Teste Pessoa']);
        $response = $this->getJson('/search?query=teste')->assertOk();
        $this->assertEqualsCanonicalizing(['user', 'movie', 'game'], array_column($response->json(), 'type'));
        $this->getJson('/search?query=teste&exclude=user')->assertOk()->assertJsonCount(2);
        $this->getJson('/games/search?query=teste')->assertOk()->assertJsonPath('results.0.id', 3498);
    }

    public function test_catalogue_failure_does_not_crash_the_site(): void
    {
        Http::fake(['*' => Http::failedConnection()]);
        $this->get('/')->assertOk();
        $this->get('/games')->assertOk();
        $this->get('/filme/999')->assertNotFound();
        $this->get('/games/999')->assertNotFound();
        $this->getJson('/search?query=teste')->assertOk();
        config(['services.google.client_id' => null]);
        $this->get('/auth/google')->assertRedirect('/login')->assertSessionHas('error');
    }

    public function test_saved_games_remain_searchable_and_open_during_rawg_outage(): void
    {
        Game::create([
            'rawg_id' => 3498,
            'name' => 'Jogo salvo',
            'description' => 'Descrição salva.',
        ]);
        Http::fake(['*' => Http::failedConnection()]);

        $this->getJson('/games/search?query=salvo')->assertOk()
            ->assertJsonPath('results.0.id', 3498);
        $this->get('/games/3498')->assertOk()->assertSee('Jogo salvo');
        $this->get('/games/999')->assertNotFound();
    }

    public function test_igdb_supplies_new_games_when_rawg_is_unavailable(): void
    {
        config([
            'services.rawg.key' => null,
            'services.igdb.client_id' => 'test-id',
            'services.igdb.client_secret' => 'test-secret',
        ]);
        Http::fake([
            'id.twitch.tv/oauth2/token' => Http::response(['access_token' => 'test-token']),
            'api.igdb.com/v4/games' => Http::response([[
                'id' => 1942,
                'name' => 'The Witcher 3',
                'summary' => 'Uma aventura.',
                'cover' => ['image_id' => 'cover123'],
                'rating' => 90,
                'rating_count' => 100,
                'genres' => [['name' => 'Adventure']],
                'platforms' => [['name' => 'PC']],
            ]]),
        ]);

        $id = IgdbService::ID_OFFSET + 1942;
        $this->get('/games')->assertOk()->assertSee('The Witcher 3')->assertSee('IGDB');
        $this->getJson('/games/search?query=witcher')->assertOk()->assertJsonPath('results.0.id', $id);
        $this->get("/games/{$id}")->assertOk()->assertSee('Uma aventura.');
        $this->assertDatabaseHas('table_games', ['rawg_id' => $id, 'name' => 'The Witcher 3']);
        $this->actingAs(User::factory()->create());
        $this->postJson("/game/{$id}/like")->assertOk();
        $this->postJson("/game/{$id}/watchlist")->assertOk()->assertJsonPath('added', true);
    }

    public function test_profile_displays_saved_posters_without_repeating_the_image_host(): void
    {
        $this->actingAs($user = User::factory()->create());
        $movie = Movie::create(['tmdb_id' => 550, 'title' => 'Meu filme', 'poster' => 'https://image.tmdb.org/t/p/w500/poster.jpg']);
        $user->likedMovies()->attach($movie);
        $user->watchlistMovies()->attach($movie);
        $this->get("/profile/{$user->arroba}")->assertOk()
            ->assertSee('src="https://image.tmdb.org/t/p/w500/poster.jpg"', false)
            ->assertDontSee('w500https://', false);
        Http::assertNothingSent();
    }

    public function test_photo_upload_and_password_change_require_the_correct_current_password(): void
    {
        Storage::fake('public');
        $this->actingAs($user = User::factory()->create());
        $this->put("/profile/{$user->arroba}", [
            'name' => 'Não deve salvar', 'profile_picture' => UploadedFile::fake()->image('foto.jpg'),
            'current_password' => 'errada', 'new_password' => 'senha-nova123', 'new_password_confirmation' => 'senha-nova123',
        ])->assertSessionHasErrors('current_password');
        $this->assertNull($user->fresh()->profile_picture);
        $this->assertCount(0, Storage::disk('public')->allFiles());
        $this->put("/profile/{$user->arroba}", [
            'name' => 'Nome atualizado', 'profile_picture' => UploadedFile::fake()->image('foto.jpg'),
            'current_password' => 'password', 'new_password' => 'senha-nova123', 'new_password_confirmation' => 'senha-nova123',
        ])->assertSessionHasNoErrors()->assertRedirect();
        $this->assertTrue(Hash::check('senha-nova123', $user->fresh()->password));
        Storage::disk('public')->assertExists($user->fresh()->profile_picture);
    }

    public function test_list_creation_rejects_invalid_media_and_deduplicates_valid_items(): void
    {
        $this->actingAs(User::factory()->create());
        $this->postJson('/lists', ['name' => 'Inválida', 'media' => ['não é JSON']])->assertUnprocessable();
        $this->postJson('/lists', ['name' => 'Inválida', 'media' => [json_encode([
            'media_type' => 'invalid', 'media_id' => -1, 'title' => 'Inválido',
        ])]])->assertUnprocessable();
        $this->assertDatabaseCount('table_lists', 0);
        $item = json_encode(['media_type' => 'movie', 'media_id' => 550, 'title' => 'Filme: com dois-pontos']);
        $this->post('/lists', ['name' => 'Válida', 'media' => [$item, $item]])->assertSessionHasNoErrors()->assertRedirect();
        $this->assertDatabaseCount('table_list_items', 1);
    }

    public function test_public_lists_do_not_offer_delete_controls_to_visitors(): void
    {
        $owner = User::factory()->create();
        ListModel::create(['user_id' => $owner->id, 'name' => 'Lista pública']);
        $this->get("/@{$owner->arroba}/lists")->assertOk()->assertSee('Lista pública')
            ->assertDontSee('title="Excluir lista"', false);
    }
}
