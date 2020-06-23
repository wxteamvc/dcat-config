<?php
namespace Dcat\Admin\Extension\Config\Http\Controllers;


use Dcat\Admin\Extension\Config\Http\Models\ConfigGroup;
use Dcat\Admin\Grid;
use Dcat\Admin\Form;
use Illuminate\Routing\Controller;
use Dcat\Admin\Layout\Content;

class AddGroupController extends Controller
{

    protected $name = "添加分组项";

    public function index(Content $content)
    {
        return $content
            ->title($this->name)
            ->body($this->grid());
    }

    public function grid()
    {

        return Grid::make(new ConfigGroup(), function (Grid $grid) {

            $grid->id('ID')->sortable();
            $grid->column('sort', '排序')->editable(true);
            $grid->column('g_name', '分组名称')->editable(true);
            $grid->column('g_key', '分组键名')->badge();

            $grid->quickCreate(function (Grid\Tools\QuickCreate $create) {
                $create->integer('sort', '排序');
                $create->text('g_name', '分组名称');
                $create->text('g_key', '分组键名');
            });

            // 关闭筛选按钮
            $grid->disableFilter();
            // 关闭详情按钮
            $grid->disableViewButton();
            // 关闭编辑按钮
            $grid->disableEditButton();
            // 关闭创建按钮
            $grid->disableCreateButton();
        });

    }

    public function store()
    {
        return $this->form()->store();
    }

    public function update($id)
    {
        return $this->form()->update($id);
    }

    public function form()
    {
        return Form::make(new ConfigGroup(), function (Form $form) {
            $form->number('sort');
            $form->text('g_name', '分组名称');
            $form->text('g_key', '分组键名');
        });
    }

    public function destroy($id)
    {
        return $this->form()->destroy($id);
    }
}
