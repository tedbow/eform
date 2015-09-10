<?php
/**
 * @file
 * Contains \Drupal\eform\Controller\EFormTypeController.
 */

namespace Drupal\eform\Controller;

use Drupal\Core\Access\AccessResult;
use Drupal\eform\Entity\EFormType;
use Drupal\eform\Form\EFormTypeForm;
class EFormTypeController extends EFormControllerBase {

  /**
   * Return Submissions page for the Entityform type.
   *
   * @param \Drupal\eform\Entity\EFormType $eform_type
   * @param null $views_display_id
   *
   * @return array
   */
  public function submissionsPage(EFormType $eform_type, $views_display_id = NULL) {
    $eform_type->loadDefaults();
    $view_name = $eform_type->getAdminView();
    $output = parent::submissionsPage($eform_type, $views_display_id, $view_name, 'entity.eform_type.submissions');
    return $output;
  }

  /**
   * Return Submissions page for the Entityform type.
   *
   * @param \Drupal\eform\Entity\EFormType $eform_type
   * @param null $views_display_id
   *
   * @return array
   */
  public function userSubmissionsPage(EFormType $eform_type, $views_display_id = NULL) {
    $eform_type->loadDefaults();
    $view_name = $eform_type->getUserView();
    $output = parent::submissionsPage($eform_type, $views_display_id, $view_name, 'entity.eform_submission.user_submissions');
    return $output;
  }

  public function submissionsPageAccess(EFormType $eform_type, $views_display_id = NULL) {
    return AccessResult::allowedIf($eform_type->getAdminView() != EFormTypeForm::VIEW_NONE);
  }

  public function userSubmissionsTitle(EFormType $eform_type, $views_display_id = NULL) {
    return $this->t('@form_label: Your previous submissions', ['@form_label' => $eform_type->label()]);
  }


}
