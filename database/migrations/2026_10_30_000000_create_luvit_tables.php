<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('table_users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('arroba', 20)->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->text('profile_picture')->nullable();
            $table->text('bio')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });
        Schema::create('table_movies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tmdb_id')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('poster')->nullable();
            $table->string('genre')->nullable();
            $table->date('release_date')->nullable();
            $table->float('rating')->nullable();
            $table->unsignedInteger('duration')->nullable();
            $table->string('language')->nullable();
            $table->string('director')->nullable();
            $table->text('cast')->nullable();
            $table->string('imdb_id')->nullable();
            $table->timestamps();
        });
        Schema::create('table_games', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rawg_id')->unique();
            $table->string('name');
            $table->string('slug')->nullable();
            $table->date('released')->nullable();
            $table->text('background_image')->nullable();
            $table->float('rating')->nullable();
            $table->unsignedInteger('metacritic')->nullable();
            $table->unsignedInteger('playtime')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
        foreach (['table_likes', 'table_watchlist', 'table_reviews'] as $name) {
            Schema::create($name, function (Blueprint $table) use ($name) {
                $table->id();
                $table->foreignId('user_id')->constrained('table_users')->cascadeOnDelete();
                $table->foreignId('movie_id')->nullable()->constrained('table_movies')->cascadeOnDelete();
                $table->foreignId('game_id')->nullable()->constrained('table_games')->cascadeOnDelete();
                if ($name === 'table_reviews') {
                    $table->text('review');
                    $table->unsignedTinyInteger('rating')->nullable();
                    $table->foreignId('parent_id')->nullable()->constrained('table_reviews')->cascadeOnDelete();
                } else {
                    $table->unique(['user_id', 'movie_id']);
                    $table->unique(['user_id', 'game_id']);
                }
                $table->timestamps();
            });
        }
        Schema::create('table_lists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('table_users')->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });
        Schema::create('table_list_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('list_id')->constrained('table_lists')->cascadeOnDelete();
            $table->string('media_type', 10);
            $table->unsignedBigInteger('media_id');
            $table->string('title');
            $table->text('poster_path')->nullable();
            $table->unsignedInteger('position')->default(0);
            $table->unique(['list_id', 'media_type', 'media_id']);
            $table->timestamps();
        });
        Schema::create('table_follows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('follower_id')->constrained('table_users')->cascadeOnDelete();
            $table->foreignId('following_id')->constrained('table_users')->cascadeOnDelete();
            $table->unique(['follower_id', 'following_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        foreach (['table_follows', 'table_list_items', 'table_lists', 'table_reviews', 'table_watchlist', 'table_likes', 'table_games', 'table_movies', 'password_reset_tokens', 'table_users'] as $table) {
            Schema::dropIfExists($table);
        }
    }
};
