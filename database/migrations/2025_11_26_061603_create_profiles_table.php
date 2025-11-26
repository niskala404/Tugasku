<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfilesTable extends Migration
{
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['sekolah', 'kuliah', 'kerja']);
            $table->string('school_type')->nullable(); // SMA / SMK
            $table->string('major')->nullable();       // jurusan / prodi
            $table->string('college_name')->nullable();
            $table->string('company')->nullable();
            $table->integer('age')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('profiles');
    }
}
