<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhotoCateTable extends Migration
{
    protected $table = 'photo_cate';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable($this->table)) {
            Schema::create('photo_cate', function (Blueprint $table) {
                //表的设计
                $table->engine = 'InnoDB';
                $table->charset = 'utf8mb4';
                $table->collation = 'utf8mb4_general_ci';
                //表的数据结构
                $table->increments('photocateid')->commit('主键ID');
                $table->integer('userid')->commit('用户id');
                $table->string('pname')->commit('相册名称');
                $table->integer('pid')->default(0)->commit('相册分类0-顶级分类');
                $table->smallInteger('share')->default(2)->commit('是否分享1-分享2-不分享');
                $table->smallInteger('status')->default(1)->commit('是否使用1-使用2-不适用');
                $table->smallInteger('is_del')->default(1)->commit('是否删除1-不删除2-删除');
                $table->smallInteger('is_pv_use')->default(1)->commit('观看权限1-所有人2-指定人3-自己');
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
            Schema::dropIfExists('photo_cate');
        }

    }
}
