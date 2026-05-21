<?php
namespace SimaBase\Admin\Customizer;

use SimaBase\Admin\Customizer;

class SocialManager
{

    protected Customizer $customizer;

    protected array $socialMediaPlatforms = [];
    protected array $values = [];

    public function __construct(Customizer $customizer)
    {
        $this->customizer = $customizer;

        $this->customizer->build(function(Panel $panel) {

            $section = $panel->addSection('social', "Social");
            foreach ($this->socialMediaPlatforms as $key => $name) {
                $section->addTextSetting("social_{$key}", "$name URL");
            }

        });
    }



    public function addPlatform(string $key, string $name){
        $this->socialMediaPlatforms[$key] = $name;

        return $this;
    }

    public function getValue(string $key){
        if(empty($this->values[$key])){
            $this->values[$key] = get_theme_mod("social_{$key}");
        }

        return $this->values[$key];
    }



    public function addInstagram() {
        return $this->addPlatform('instagram', 'Instagram');
    }

    public function addFacebook() {
        return $this->addPlatform('facebook', 'Facebook');
    }

    public function addTikTok() {
        return $this->addPlatform('tiktok', 'TikTok');
    }

    public function addLinkedIn() {
        return $this->addPlatform('linkedin', 'LinkedIn');
    }

    public function addYouTube() {
        return $this->addPlatform('youtube', 'YouTube');
    }

    public function addX() {
        return $this->addPlatform('x', 'X');
    }

    public function addThreads() {
        return $this->addPlatform('threads', 'Threads');
    }



    public function getInstagram() {
        return $this->getValue('instagram');
    }

    public function getFacebook() {
        return $this->getValue('facebook');
    }

    public function getTikTok() {
        return $this->getValue('tiktok');
    }

    public function getLinkedIn() {
        return $this->getValue('linkedin');
    }

    public function getYouTube() {
        return $this->getValue('youtube');
    }

    public function getX() {
        return $this->getValue('x');
    }

    public function getThreads() {
        return $this->getValue('threads');
    }

}