<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablesUserinfo extends Migration
{
    protected $table = 'userinfo';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable($this->table)) {
            Schema::create('userinfo', function (Blueprint $table) {
                //表的设计
                $table->engine = 'InnoDB';
                $table->charset = 'utf8mb4';
                $table->collation = 'utf8mb4_general_ci';
                //表的数据结构
                $table->increments('userid')->commit('主键ID');
                $table->string('usercode')->commit('账号');
                $table->string('password')->commit('密码');
                $table->string('img_path')->nullable()->default(NULL)->commit('图片缩略图');
                $table->string('email')->nullable()->default(NULL)->commit('邮箱');
                $table->integer('iphone')->nullable()->default(NULL)->commit('手机');
                $table->string('birthday')->nullable()->default(NULL)->commit('生日');
                $table->integer('sex')->nullable()->default(NULL)->commit('性别');
                $table->integer('status')->default(1)->commit('状态-1-使用2-禁用-3-删除');
                $table->string('name')->nullable()->default('匿名')->commit('用户名称');
                $table->timestamps();
            });
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable($this->table) || \DB::table($this->table)->count() <1) {
            Schema::dropIfExists('userinfo');
        }

    }
}
