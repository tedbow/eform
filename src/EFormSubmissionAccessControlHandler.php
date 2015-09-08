<?php
/**
 * @file
 * Contains \Drupal\eform\EFormSubmissionAccessControlHandler.
 */

namespace Drupal\eform;


use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;
use Drupal\eform\Entity\EFormSubmission;

class EFormSubmissionAccessControlHandler extends EntityAccessControlHandler{

  protected function checkAccess(EntityInterface $entity, $operation, $langcode, AccountInterface $account) {
    if ($operation == 'submit') {
      /** @var \Drupal\eform\entity\EFormsubmission $entity */
      $roles = $entity->getEFormType()->roles;
      $eform_roles = $account->getRoles();
      return AccessResult::allowed();

    }
    return parent::checkAccess($entity, $operation, $langcode, $account); // TODO: Change the autogenerated stub
  }

  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return parent::checkCreateAccess($account, $context, $entity_bundle); // TODO: Change the autogenerated stub
  }

  public function access(EntityInterface $entity, $operation, $langcode = LanguageInterface::LANGCODE_DEFAULT, AccountInterface $account = NULL, $return_as_object = FALSE) {
    return parent::access($entity, $operation, $langcode, $account, $return_as_object); // TODO: Change the autogenerated stub
  }
  public function checkSubmitAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowed();
  }

}
