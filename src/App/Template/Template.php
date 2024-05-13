<?php
namespace App\Template;

class Template extends Configuration
{
    private Layout $layout;

    private View $view;

    private bool $terminal = false;

    public function setView($view_name): void
    {
        $view = new View();

        if (false === $this->isTerminal() && null === $this->getLayout()) {
            throw new \Exception('Cannot set view if layout is not set, for non terminal views.');
        }

        if (false === (2 === (count(explode('/', $view_name))))) {
            throw new \Exception('View must be set as \'folder/view\'');
        }

        if (false === array_key_exists(explode('/', $view_name)[0], $this->views())) {
            throw new \Exception(sprintf('Could not resolve \'%s\' into view.', $view_name));
        }

        if (false === in_array(explode('/', $view_name)[1], $this->views()[explode('/', $view_name)[0]])) {
            throw new \Exception(sprintf('Could not resolve \'%s\' into view.', $view_name));
        }

        $this->view = $view->setViewName($view_name);
    }

    public function setLayout(string $layoutName): void
    {
        $layout = new Layout();

        if (false === in_array($layoutName, $this->layouts())) {
            throw new \Exception(sprintf('Layout %s not found.', $layoutName));
        }

        $this->layout = $layout->setLayoutName($layoutName);
    }

    public function getView(): View
    {
        return $this->view;
    }

    public function getLayout(): Layout
    {
        return $this->layout;
    }

    public function isTerminal(): bool
    {
        return $this->terminal;
    }

    public function setTerminal($terminal): void
    {
        $this->terminal = $terminal;
    }

}
