<?php
namespace SimaBase\Admin\Theme\Customizer\Panel\Section;

if(!defined('ABSPATH')) {
    exit; // Accessed directly
}

use SimaBase\Admin\Theme\Customizer\Panel\Section;

class Setting
{

    protected Section $section;

    protected string $id;

    protected string $label;

    public function __construct(Section $section, string $id, string $label)
    {
        $this->section = $section;
        $this->id = $id;
        $this->label = $label;
    }

}