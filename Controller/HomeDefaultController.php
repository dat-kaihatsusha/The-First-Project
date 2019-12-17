<?php

class HomeDefaultController extends ControllerBase
{
    public function HomeDefault()
    {
        $data = [];
        $this->RenderViewDefault('homedefault.trang-chu', $data);
    }
}

?>

