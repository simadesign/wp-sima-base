<?php
namespace SimaBase\Admin;

if(!defined('ABSPATH')) {
    exit; // Accessed directly
}

use SimaBase\Admin\Customizer\Panel;
use SimaBase\Admin\Customizer\SocialManager;
use WP_Customize_Manager;

class Customizer
{

    protected SocialManager $socialManager;

    protected array $panels = [];

    protected array $builders = [];

    public function __construct(){

        $this->socialManager = new SocialManager($this);

        add_action('customize_register', [$this, '_registerThemeCustomizing'], 10);

    }

    public function _registerThemeCustomizing(WP_Customize_Manager $wp_customize): void
    {
        // https://developer.wordpress.org/themes/customize-api/customizer-objects/

        foreach($this->builders as $builder){
            [$id, $title, $callback] = $builder;

            $panel = $this->getPanel($id);
            if($panel){
                $callback($panel);
            } else {
                $callback($this->addPanel($id, $title));
            }
        }

        /** @var Panel $panel */
        foreach($this->getPanels() as $panel) {

            $thePanel = $wp_customize->get_panel($panel->getId());
            if(empty($thePanel)){
                $wp_customize->add_panel($panel->getId(), $panel->getData());
            }

            foreach($panel->getSections() as $section) {

                $wp_customize->add_section($section->getId(), $section->getData());
                foreach($section->getSettings() as $setting) {

                    $wp_customize->add_setting($setting['id']);
                    $wp_customize->add_control($setting['id'], array_merge($setting, [
                        'section' => $section->getId(),
                    ]));

                }

            }

        }

    }


    public function getSocial(): SocialManager
    {
        return $this->socialManager;
    }


    public function getPanels(): array
    {
        return $this->panels;
    }

    public function getPanel(string $id): ?Panel
    {
        return !empty($this->panels[$id])? $this->panels[$id] : null;
    }

    public function addPanel(string $id, string $title): Panel
    {
        $panel = new Panel($this, $id, $title);

        $this->panels[$id] = $panel;

        return $panel;
    }

    public function buildPanel(string $id, string $title, callable $callback): void
    {
        $this->builders[] = [$id, $title, $callback];
    }

    public function build(callable $callback): void
    {
        $this->buildPanel('simadesign', "SiMa Theme", $callback);
    }

}