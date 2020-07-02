<?php
namespace Dcat\Admin\Extension\Config\Http\Controllers;


use Dcat\Admin\Admin;
use Dcat\Admin\Extension\Config\Http\Models\ConfigGroup;
use Dcat\Admin\Extension\Config\Http\Models\Configs;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Illuminate\Routing\Controller;
use Dcat\Admin\Layout\Content;

class AddConfigController extends Controller
{
    protected $name = "添加配置项";

    protected $can_widgets = [
        'text' => '文本',
        'select' => '下拉单选',
        'file' => '文件上传',
        'image' => '图片上传',
        'editor' => '富文本框',
        'textarea' => '文本框',
        'radio' => '单选',
        'switch' => '开关',
        'email' => '邮箱',
        'password' => '密码',
        'url' => '链接',
        'ip' => 'IP地址',
        'mobile' => '手机',
    ];

    public function index(Content $content)
    {
        return $content
            ->title($this->name)
            ->body($this->grid());
    }

    public function grid()
    {

        return Grid::make(new Configs(), function (Grid $grid) {

            $grid->id('ID')->sortable();

            $groups = ConfigGroup::pluck('g_name', 'g_key')->toArray();
            $can_widgets = $this->can_widgets;

            $grid->column('groups', '所属分组')->display(function ($value) use($groups){
                return $groups[$value];
            });
            $grid->column('widget', '类型')->display(function($value) use($can_widgets){
                return $can_widgets[$value];
            });

            $grid->column('title', '变量标题');
            $grid->column('key', '变量名')->display(function($value){
                return "{ {$this->groups}.{$value} }";
            });

            // 开启快捷搜索
            $grid->quickSearch(function($model, $query){
                $model->where('title', 'like' ,"%$query%")->orWhere('key', 'like' ,"%$query%");
            });

            $grid->enableDialogCreate();
            // 关闭筛选按钮
            $grid->disableFilter();
            // 关闭详情按钮
            $grid->disableViewButton();
            // 关闭编辑按钮
            $grid->disableEditButton();
        });

    }

    public function create(Content $content)
    {
        return $content->body($this->form());
    }

    public function edit($id, Content $content){
        return $content ->title('编辑')->body($this->form()->edit($id));
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
        return Form::make(new Configs(), function (Form $form) {
            $groups = ConfigGroup::getAllGroup();
            $form->select('groups', '所属分组')->options($groups)->rules('required');
            $form->select('widget', '类型')->options($this->can_widgets)->rules('required');

            $form->tags('options', '设置选项')
                ->help('前部填写键名，使用"|"隔开，尾部填写键值，例如：全部|all');
            $form->text('title', '变量标题')->help('例如：系统名称')->rules('required');
            $form->text('key', '变量名')->help('例如：web_name')->rules('required');
            $form->text('tips', '提示信息');

//            if($form->model()->widget == 'select' || $form->model()->widget == 'radio' ){
//                $options_status = "options.show();";
//            }else{
                $options_status = "options.hide();";
//            }

            Admin::script(
                <<<JS
(function(){
    var options = $('input[name="options[]"]').parents('.form-group');
    
    {$options_status}
    
    $('select[name="widget"]').on('change', function(){
        switch ($(this).val()) {
          case 'select':
              options.show();
              break;
          case 'radio':
             options.show();
             break; 
          default:
              options.hide();
              break;
        }
    })
})();
JS
            );
        });
    }

    public function destroy($id)
    {
        return $this->form()->destroy($id);
    }

}
