<?php
namespace App\Core\Form;

class TextareaField extends BaseField{
    public function renderInput()
    {
        return sprintf('<textarea name="%s" class="form-control %s"></textarea>',
        $this->attribute,
        $this->model->hasError($this->attribute) ? 'is-invalid' : '',
        $this->model->{$this->attribute}
    );
    }

}