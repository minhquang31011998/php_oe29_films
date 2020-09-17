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
                $table->increments('id')->comment('id');
                $table->string('name')->comment('ten');
                $table->string('email')->comment('email dang nhap');
                $table->integer('phone')->comment('so dien thoai');
                $table->string('address')->nullable()->comment('dia chi');
                $table->timestamp('email_verified_at')->comment('email xac thuc');
                $table->integer('is_active')->default(1)->comment('trang thai hoat dong');
                $table->integer('role')->default(2)->comment('vai tro');
                $table->string('password')->comment('mat khau');
                $table->rememberToken()->comment('ma token');
                $table->timestamps();
                $table->softDeletes()->comment('thoi gian xoa');
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
