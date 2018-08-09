<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhotoTable extends Migration
{
    protected $table = 'photo';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable($this->table)) {
            Schema::create('photo', function (Blueprint $table) {
                //表的设计
                $table->engine = 'InnoDB';
                $table->charset = 'utf8mb4';
                $table->collation = 'utf8mb4_general_ci';
                //表的数据结构
                $table->increments('photoid')->commit('主键ID');
                $table->integer('photocateid')->commit('分类id');
                $table->integer('userid')->commit('用户id');
                $table->string('img_thumb')->commit('缩略图');
                $table->string('img_path')->commit('图片路径');
                $table->string('img_name')->commit('图片现在名称');
                $table->string('img_origin')->default(NULL)->commit('图片原名称');
                $table->string('type')->default(NULL)->commit('图片真实类型');
                $table->string('ext')->default(NULL)->commit('后缀名');
                $table->integer('likecount')->default(0)->commit('点赞数量');
                $table->integer('pv')->default(0)->commit('观看数目');
                $table->smallInteger('is_del')->default(1)->commit('是否删除1-未删除2-删除');
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
            Schema::dropIfExists('photo');
        }

    }
}
