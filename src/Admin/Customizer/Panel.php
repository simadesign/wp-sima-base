<?php
namespace SimaBase\Admin\Customizer;

if(!defined('ABSPATH')) {
    exit; // Accessed directly
}

use SimaBase\Admin\Customizer;
use SimaBase\Admin\Customizer\Panel\Section;

class Panel
{

    protected Customizer $customizer;
    protected array $sections = [];

    protected string $id;

    protected string $title;
    protected string $description = "";
    protected int $priority = 1;

    public function __construct(Customizer $customizer, $id, $title){
        $this->customizer = $customizer;
        $this->id = $id;
        $this->title = $title;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getPriority(): int
    {
        return $this->priority;
    }

    public function getData(): array
    {
        return [
            'title' => __($this->title),
            'description' => __($this->description),
            'priority' => $this->priority,
        ];
    }

    public function getSections(): array
    {
        return $this->sections;
    }



    public function setPriority(int $priority): static
    {
        $this->priority = $priority;

        return $this;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function addSection(string $id, string $title): Section
    {
        $section = new Section($this, $id, $title);

        $this->sections[] = $section;

        return $section;
    }

}