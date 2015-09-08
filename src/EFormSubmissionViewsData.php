<?php
/**
 * @file
 * Contains \Drupal\eform\EFormSubmissionViewsData.
 */

namespace Drupal\eform;


use Drupal\views\EntityViewsData;

class EFormSubmissionViewsData extends EntityViewsData{
  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();
    $data['eform_submission']['eform_submission_bulk_form'] = array(
      'title' => t('Bulk update'),
      'help' => t('Add a form element that lets you run operations on multiple EForm Submissions.'),
      'field' => array(
        //'id' => 'eform_submission_bulk_form',
        'id' => 'bulk_form',
      ),
    );
    return $data;
  }

}
