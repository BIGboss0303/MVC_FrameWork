<?php


namespace App\Core\Form;
use App\Core\Model;
abstract class BaseField{
    public function __construct(public Model $model,
    public string $attribute,
    )
    {}
    abstract public function renderInput(); 
    public function __toString()
    {
       return sprintf('
            <div class="mb-3">
                <label class="form-label">%s</label>
                %s
                <div class="invalid-feedback">
                    %s
                </div>
            </div>',
            $this->model->getLabel($this->attribute),
            $this->renderInput(),
            $this->model->getFirstError($this->attribute) 
        );
    }
}
