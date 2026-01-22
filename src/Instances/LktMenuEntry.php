<?php

namespace Lkt\Menus\Instances;

use Lkt\Menus\Generated\GeneratedLktMenuEntry;

class LktMenuEntry extends GeneratedLktMenuEntry
{
    const COMPONENT = 'lkt-menu-entry';

    public function getReadMenuTo(): string
    {
        if ($this->typeIsRelativeUrl()) return $this->getUrl();
        if ($this->typeIsFullUrl()) return $this->getUrl();

        if ($this->typeIsWebItems()) {
            return "/admin/web-items/{$this->getComponent()}";
        }

        if ($this->typeIsWebPages()) {
            return "/admin/web-pages/{$this->getComponent()}";
        }
        return '';
    }

    public function postProcessRead(array $data): array
    {
        if ($this->accessPolicy?->name === 'r-app-menu') {

            if ($this->typeIsWebItems() && $this->isAnonymous() && !$this->getName()) {
                $data['text'] = $this->getComponent();
            }

            return [
                'type' => 'entry',
                'anchor' => $data,
            ];
        }
        return $data;
    }
}