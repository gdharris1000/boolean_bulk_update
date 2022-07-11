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

        //Get list of content types
        $type_options = static::getTypeOptions();

        //Get selected content type
        if (empty($form_state->getValue('type'))) {
            $selected_type = key($type_options);
        } 
        else {
            $selected_type = $form_state->getValue('type');
        }

        //Content Type drop-down menu
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

        //Field drop-down menu
        $form['field'] = [
            '#type' => 'select',
            '#title' => t('Field'),
            '#description' => t('Select field'),
            '#prefix' => '<div id="field-selection">',
            '#suffix' => '</div>',
            '#options' => static::getFieldOptions($selected_type)
        ];

        //New value
        $form['new_value'] = [
            '#type' => 'checkbox',
            '#title' => t('New value'),
            '#Description' => t('Check box for "TRUE", leave unchecked for "FALSE"')
        ];

        //Submit button
        $form['submit'] = [
            '#type' => 'submit',
            '#value' => t('Submit')
        ];

        return $form;
    }

    //Submit Form

    public function submitForm(array &$form, FormStateInterface $form_state) {
        
        $content_type = $form_state->getValue('type');
        $field = $form_state->getValue('field');
        $new_value = $form_state->getValue('new_value');
        
        //Get node ids
        $nids = \Drupal::entityQuery('node')->condition('type', $content_type)->execute();

        //Batch setup
        $batch = [
            'title' => $this->t('Updating nodes'),
            'operations' => [['boolean_bulk_update_execute', [$nids, $field, $new_value]]],
            'finished' => 'boolean_bulk_update_finished'
        ];
        //Run batch
        batch_set($batch);
    }

    // Returns an array listing all the site's content types
    public function getTypeOptions(): array {
        $entity_type_manager = \Drupal::service('entity_type.manager');
        $content_types = $entity_type_manager->getStorage('node_type')->loadMultiple();
        $types = ['Select Content Type'];

        foreach($content_types as $content_type) {
            $types[] = [$content_type->id() => $content_type->label()];
        }

        return $types;
    }

    // Returns an array listing all the boolean fields for the content type provided
    public function getFieldOptions($type): array {
        $entity_field_manager = \Drupal::service('entity_field.manager');
        $field_list = $entity_field_manager->getFieldDefinitions('node', $type);
        $field_options = ['Select field'];

        foreach($field_list as $field) {
            if ($field->getType() == 'boolean') {
                $field_options[] = [$field->getName() => $field->getLabel()];
            }
        }

        return $field_options;
    }

    //Ajax function to update the $form['fields'] options when a content type is selected from $form['type']
    public function getFields(array &$form, FormStateInterface $form_state) {
        return $form['field'];
    }

}
