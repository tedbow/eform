<?php
/**
 * Author: Ted Bowman
 * Date: 4/16/15
 * Time: 9:34 AM
 */

namespace Drupal\eform\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


class EFormSubmissionForm extends ContentEntityForm {
  /**
   * The entity being used by this form.
   *
   * @var \Drupal\eform\Entity\EFormSubmission
   */
  protected $entity;

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
    /*
    $route = $this->getRequest()->get('_route');

    if (!$this->entity->isNew() && $this->currentUser()->id() != $this->entity->getAuthor()->id()) {
      $this->setOperation('submit');
    }
    */
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
    // Add redirect function callback.
    if (isset($actions['submit'])) {
      $actions['submit'][] =  '::eformRedirect';
    }
    if ($entity->getEFormType()->isDraftable()) {
      $actions['draft'] = array(
        '#type' => 'submit',
        '#value' => $this->t('Save Draft'),
        '#validate' => array('::validate'),
        '#submit' => array('::submitForm', '::saveDraft', '::eformRedirect'),
        '#weight' => -100,
      );
    }

    return $actions;
  }
  public function eformRedirect(array $form, FormStateInterface $form_state) {
    /** @var \Drupal\eform\Entity\EFormSubmission $eform_submission */
    $eform_submission = $this->entity;
   // $form_state->get
    if (!$eform_submission->isDraft()) {
      $redirect_params = [
        'eform_type' => $eform_submission->getType(),
        'eform_submission' => $eform_submission->id(),
      ];
      $form_state->setRedirect('entity.eform_submission.confirm', $redirect_params);
    }
  }
}
