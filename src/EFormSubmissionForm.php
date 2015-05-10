<?php
/**
 * Author: Ted Bowman
 * Date: 4/16/15
 * Time: 9:34 AM
 */

namespace Drupal\eform;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;


class EFormSubmissionForm extends ContentEntityForm {
  public function save(array $form, FormStateInterface $form_state) {
    return parent::save($form, $form_state);
  }
  public function saveDraft(array $form, FormStateInterface $form_state) {
    /** @var \Drupal\eform\Entity\EFormSubmission $entity */
    $entity = $this->entity;

    $entity->setDraft(EFORM_DRAFT);
    return parent::save($form, $form_state);
  }

  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);
    if ($this->entity->isNew()) {
      $form['revision_uid'] = array(
        '#type' => 'value',
        '#value' => $this->currentUser()->id(),
      );
    }
    return $form;
  }

  protected function actions(array $form, FormStateInterface $form_state) {
    $actions = parent::actions($form, $form_state);
    /** @var \Drupal\eform\entity\EFormsubmission $entity */
    $entity = $this->entity;
    if ($entity->getEFormType()->isDraftable()) {
      $actions['draft'] = array(
        '#type' => 'submit',
        '#value' => $this->t('Save Draft'),
        '#validate' => array('::validate'),
        '#submit' => array('::submitForm', '::saveDraft'),
        '#weight' => -100,
      );
    }

    return $actions;
  }

}
