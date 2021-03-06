<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('cpf')->unique();
            $table->date('data_nascimento');
            $table->string('certificado')->nullable();
            $table->timestamp('certificado_not_before')->nullable();
            $table->timestamp('certificado_not_after')->nullable();
            $table->string('certificado_dn')->nullable();
            $table->string('certificado_issuer_dn')->nullable();
            $table->integer('qtd_acessos')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
