<?php

/**
 * @file
 * Contains \Drupal\eform\Plugin\views\field\EFormSubmissionBulkForm.
 */

namespace Drupal\eform\Plugin\views\field;

use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\system\Plugin\views\field\BulkForm;
use Drupal\user\UserInterface;

/**
 * Defines a eform submission operations bulk form element.
 *
 * @ViewsField("eform_submission_bulk_form")
 */
class EFormSubmissionBulkForm extends BulkForm {


  /**
   * {@inheritdoc}
   */
  protected function emptySelectedMessage() {
    return $this->t('No users selected.');
  }

}
