<?php
/**
 * @file
 * Contains \Drupal\eform\Form\EFormTypeDefaultsForm.
 */

namespace Drupal\eform\Form;


use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\eform\Entity\EFormType;
use Drupal\eform\Form\EFormTypeForm;

/**
 * Form class to build EForm Type Defaults form.
 */
class EFormTypeDefaultsForm extends ConfigFormBase{

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'eform_type_defaults';
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->cleanValues()->getValues();

    $config = $this->configFactory()->getEditable('eform.type_defaults');
    $keys = array_keys($config->getRawData());
    foreach ($keys as $key) {
      $config->clear($key);
    }

    foreach ($values as $key => $value) {
      $config->set($key, $value);
    }
    $config->save();

    parent::submitForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['eform.type_defaults'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $eform_type_form = new EFormTypeForm();
    $config = $this->configFactory()->get('eform.type_defaults');
    $default_values = $config->getRawData();
    $eform_type = new EFormType($default_values, 'eform_type');
    $form += $eform_type_form->EFormTypeElements($form, $eform_type);
    return parent::buildForm($form, $form_state);
  }
}
