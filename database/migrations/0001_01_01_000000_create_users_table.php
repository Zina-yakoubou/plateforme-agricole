<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | Identité
            |--------------------------------------------------------------------------
            */

            $table->id();

            $table->string('name');

            /*
            |--------------------------------------------------------------------------
            | Coordonnées
            |--------------------------------------------------------------------------
            |
            | Le téléphone est l'identifiant utilisé pour la connexion.
            |
            */

            $table->string('telephone')->unique();

            // Email facultatif
            $table->string('email')->nullable()->unique();


            /*
            |--------------------------------------------------------------------------
            | Vérification du téléphone
            |--------------------------------------------------------------------------
            */

            $table->timestamp('telephone_verified_at')->nullable();


            /*
            |--------------------------------------------------------------------------
            | OTP
            |--------------------------------------------------------------------------
            |
            | Utilisé lors de la création du compte et pour la récupération
            | du compte.
            |
            */

            $table->string('otp_code', 6)->nullable();

            $table->timestamp('otp_expires_at')->nullable();

            $table->unsignedTinyInteger('otp_attempts')->default(0);


            /*
            |--------------------------------------------------------------------------
            | Authentification
            |--------------------------------------------------------------------------
            |
            | Le mot de passe est défini par l'utilisateur après validation
            | de son numéro de téléphone.
            |
            */

            $table->string('password');


            /*
            |--------------------------------------------------------------------------
            | État du compte
            |--------------------------------------------------------------------------
            |
            | true  = compte actif
            | false = compte désactivé
            |
            */

            $table->boolean('statut')->default(true);


            /*
            |--------------------------------------------------------------------------
            | Rôle
            |--------------------------------------------------------------------------
            |
            | Exemple :
            | R01 = Administrateur
            | R02 = DPA
            | R03 = Superviseur
            | R04 = Technicien
            | R05 = CACH
            | R06 = Agent recenseur
            |
            */

            $table->foreignId('role_id')
                ->constrained('roles', 'idRole')
                ->restrictOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Laravel
            |--------------------------------------------------------------------------
            */

            $table->rememberToken();

            $table->timestamps();
        });


        /*
        |--------------------------------------------------------------------------
        | Password reset tokens
        |--------------------------------------------------------------------------
        */

        Schema::create('password_reset_tokens', function (Blueprint $table) {

            $table->string('email')->primary();

            $table->string('token');

            $table->timestamp('created_at')->nullable();
        });


        /*
        |--------------------------------------------------------------------------
        | Sessions
        |--------------------------------------------------------------------------
        */

        Schema::create('sessions', function (Blueprint $table) {

            $table->string('id')->primary();

            $table->foreignId('user_id')
                ->nullable()
                ->index();

            $table->string('ip_address', 45)->nullable();

            $table->text('user_agent')->nullable();

            $table->longText('payload');

            $table->integer('last_activity')->index();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');

        Schema::dropIfExists('password_reset_tokens');

        Schema::dropIfExists('sessions');
    }
};