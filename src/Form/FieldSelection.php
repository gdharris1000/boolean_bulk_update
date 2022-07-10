<?php

namespace Drupal\boolean_bulk_update\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class FieldSelection extends FormBase {
    //Form Id

    public function getFormId() {
        return 'field_selection_form';
    }

    //Build Form
    public function buildForm(array $form, FormStateInterface $form_state) {

    }

    //Submit Form

    public function submitForm(arrya &$form, FormStateInterface $form_state) {
        
    }

}
