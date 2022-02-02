<?php

require_once __DIR__.'/routes.php';

class Actions extends Routes
{
    private string $title = 'Website';

    public function redirect(): void
    {
        $this->redirect = '/city';
    }

    public function listCity(): void {
        $this->view = 'city_list.html';
        $this->data = array_merge($this->data, [
            'title' => $this->title,
        ]);
    }

    public function getCity(): void {
        $this->view = 'city_get.html';
        $this->data = array_merge($this->data, [
            'title' => $this->title,
        ]);
    }

    public function addCity(): void {
        $this->view = 'city_add.html';
        $this->data = array_merge($this->data, [
            'title' => $this->title,
        ]);
    }
    
    public function submitCity(): void {
        $this->error = 'Cannot Add City => MySQL not configured yet.';
        $this->redirect = '/city';
    }

    public function removeCity(): void {
        $this->error = 'Cannot Remove City => MySQL not configured yet.';
        $this->redirect = '/city';
    }
}
