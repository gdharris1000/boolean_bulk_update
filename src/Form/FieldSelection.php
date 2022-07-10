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
        $form['intro'] = [
            '#type' => 'item',
            '#markup' => '<p>hello</p>'
        ];

        $type_options = static::getTypeOptions();

        if (empty($form_state->getValue('type'))) {
            $selected_type = key($type_options);
        } 
        else {
            $selected_type = $form_state->getValue('type');
        }

        

        $form['type'] = [
            '#type' => 'select',
            '#title' => t('Content Type'),
            '#description' => t('Select content type'),
            '#options' => $type_options,
            '#ajax' => [
                'callback' => '::getFields',
                'event' => 'change',
                'method' => 'replace',
                'wrapper' => 'field-selection'
            ]
        ];

        $form['field'] = [
            '#type' => 'select',
            '#title' => t('Field'),
            '#description' => t('Select field'),
            '#prefix' => '<div id="field-selection">',
            '#suffix' => '</div>',
            '#options' => static::getFieldOptions($selected_type)
        ];

        $form['value'] = [
            '#type' => 'checkbox',
            '#title' => t('New value'),
            '#Description' => t('Check box for "TRUE", leave unchecked for "FALSE"')
        ];

        $form['submit'] = [
            '#type' => 'submit',
            '#value' => t('Submit')
        ];

        return $form;
    }

    //Submit Form

    public function submitForm(array &$form, FormStateInterface $form_state) {
        $messenger = \Drupal::messenger()->addMessage('hello');
    }

    public function getTypeOptions(): array {
        $entity_type_manager = \Drupal::service('entity_type.manager');
        $content_types = $entity_type_manager->getStorage('node_type')->loadMultiple();
        $types = ['Select Content Type'];

        foreach($content_types as $content_type) {
            $types[] = [$content_type->id() => $content_type->label()];
        }

        return $types;
    }

    public function getFieldOptions($type): array {
        $entity_field_manager = \Drupal::service('entity_field.manager');
        $field_list = $entity_field_manager->getFieldDefinitions('node', $type);
        $field_options = ['Select field'];

        foreach($field_list as $field) {
            if ($field->getType() == 'boolean') {
                $field_options[] = [$field->getName() => $field->getLabel()];
            }
            // $field_options[] = [$field->getName() => $field->getLabel()];
        }

        return $field_options;
    }

    public function getFields(array &$form, FormStateInterface $form_state) {
        return $form['field'];
    }


    

}
