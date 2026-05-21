<?php
namespace SimaBase\Admin\Config;

use SimaBase\Core\Traits\Singleton;

class Options
{

    use Singleton;

    protected array $data;

    public $options_group = 'sima_options';

    public function __construct()
    {
        $this->data = [
            //
        ];
    }

    public function register(): void
    {
        foreach($this->data as $option_name => $option_data){
            register_setting($this->options_group, $option_name, $option_data);
        }
    }



    public function getData($option_name)
    {
        return (isset($this->data[$option_name]))? $this->data[$option_name] : null;
    }

    public function get($option_name)
    {
        $data = $this->getData($option_name);
        if(!$data){
            return null;
        }

        $type = $data['type'];
        $val = get_option($option_name, $data['default']);

        if($type === 'boolean'){
            $val = !empty($val);
        }

        return $val;
    }

}