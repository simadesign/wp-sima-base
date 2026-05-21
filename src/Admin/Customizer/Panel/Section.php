<?php
namespace SimaBase\Admin\Customizer\Panel;

use SimaBase\Admin\Customizer\Panel;

class Section
{

    protected Panel $panel;
    protected array $settings = [];

    protected string $id;

    protected string $title;
    protected string $description = "";
    protected int $priority = 1;
    protected string $capability = "edit_theme_options";

    public function __construct(Panel $panel, string $id, string $title)
    {
        $this->panel = $panel;
        $this->id = $id;
        $this->title = $title;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getSettings(): array
    {
        return $this->settings;
    }

    public function getData(): array
    {
        return [
            'title' => __($this->title),
            'description' => __($this->description),
            'panel' => $this->panel->getId(),
            'priority' => $this->priority,
            'capability' => $this->capability,
        ];
    }

    public function setPriority(int $priority): self
    {
        $this->priority = $priority;

        return $this;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function addSetting(string $id, string $label, array $args = []): self
    {
        $this->settings[$id] = array_merge($args, compact('id', 'label'));

        return $this;
    }

    public function addTextSetting(string $id, string $label, array $args = []): self
    {
        return $this->addSetting($id, $label, array_merge($args, ['type' => 'text']));
    }

    public function addTextareaSetting(string $id, string $label, array $args = []): self
    {
        return $this->addSetting($id, $label, array_merge($args, ['type' => 'textarea']));
    }

}