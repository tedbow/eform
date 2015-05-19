<?php
/**
 * Author: Ted Bowman
 * Date: 5/18/15
 * Time: 9:04 AM
 */

namespace Drupal\eform\Form;


use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\eform\Entity\EFormType;
use Drupal\eform\Form\EFormTypeForm;

class EFormTypeDefaultsForm extends ConfigFormBase{

  /**
   * Returns a unique string identifying the form.
   *
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormId() {
    return 'eform_type_defaults';
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->cleanValues()->getValues();

    $config = \Drupal::configFactory()->getEditable('eform.type_defaults');
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

  public function buildForm(array $form, FormStateInterface $form_state) {
    $eform_type_form = new EFormTypeForm();
    $eform_type = new EFormType([], 'eform_type');
    $form += $eform_type_form->EFormTypeElements($form, $eform_type);
    return parent::buildForm($form, $form_state);
  }
}
