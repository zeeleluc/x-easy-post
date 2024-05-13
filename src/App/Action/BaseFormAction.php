<?php
namespace App\Action;

use App\FormFieldValidator\FormFieldValidator;
use App\Variable;

abstract class BaseFormAction extends BaseAction
{

    private string $formRoute;

    protected array $formErrors = [];

    protected array $validatedFormValues = [];

    protected function performGet()
    {
        $this->setVariable(new Variable(
            'formValidatedValues',
            $this->getSession()->getItem('formValidatedValues'))
        );
        $this->setVariable(new Variable(
            'formErrors',
            $this->getSession()->getItem('formErrors'))
        );
    }

    abstract protected function performPost();

    abstract protected function handleForm();

    public function __construct(string $formRoute)
    {
        $this->formRoute = $formRoute;

        parent::__construct();
    }

    public function hasFormErrors(): bool
    {
        return count($this->formErrors) >= 1;
    }

    public function getValidatedFormValues(): array
    {
        return $this->validatedFormValues;
    }

    protected function validateFormValues(array $formFieldValidators): void
    {
        foreach ($formFieldValidators as $formFieldValidator) { /* @var $formFieldValidator FormFieldValidator */
            $formFieldValidator->validate();
            if (!$formFieldValidator->isValid()) {
                foreach ($formFieldValidator->getMessages() as $message) {
                    $this->formErrors[$formFieldValidator->key][] = $message;
                }
            } else {
                $this->validatedFormValues[$formFieldValidator->key] = $formFieldValidator->value;
            }
        }

        if ($this->hasFormErrors()) {
            form_errors($this->validatedFormValues, $this->formErrors);
            warning($this->formRoute, 'Fix the form errors and try again.');
        } else {
            $this->handleForm();
        }
    }
}
