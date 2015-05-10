<?php
/**
 * Author: Ted Bowman
 * Date: 4/15/15
 * Time: 2:44 PM
 *
 * @todo Does it make sense to have separate confirm page url
 *       or just confirm text on submit url?
 */

namespace Drupal\eform\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\eform\Entity\EFormType;
use Drupal\Core\Entity\Query\QueryInterface;
use Drupal\Core\Entity\Sql\SqlContentEntityStorage;


class EFormSubmissionController extends ControllerBase {

  /**
   * @var SqlContentEntityStorage $entity_storage
   */
  protected $entity_storage;



  /**
   * Provides the EForm submission form.
   *
   * @param \Drupal\eform\Entity\EFormType $eform_type
   *   The node type entity for the node.
   *
   * @return array
   *   A EForm submission form.
   */
  public function submit(EFormType $eform_type) {
    $eform_submission = $this->getSubmitEFormSubmission($eform_type);
    $resubmit_action = $eform_type->getResubmitAction();
    if ($eform_submission->isSubmitted()) {
      if ($resubmit_action == $eform_type::RESUBMIT_ACTION_DISALLOW) {
        $disallow_text = $eform_type->getDisallowText();
        $form['disallow'] = array(
          '#type' => 'processed_text',
          '#text' => $disallow_text['value'],
          '#format' => $disallow_text['format'],
        );
        return $form;
      }
    }
    $form = $this->entityFormBuilder()->getForm($eform_submission);

    return $form;
  }

  protected function getSubmitEFormSubmission(EFormType $eform_type) {

    if ($eform_type->isDraftable()) {
      $eform_submission = $this->getDraftSubmission($eform_type);
    }
    if (empty($eform_submission)) {
      $resubmit_action = $eform_type->getResubmitAction();
      if ($resubmit_action == $eform_type::RESUBMIT_ACTION_NEW || $this->currentUser()
          ->isAnonymous()
      ) {
        $eform_submission = $this->getNewSubmission($eform_type);
      }
      else {
        $query = $this->entityStorage()->getQuery();
        $query->condition('uid', $this->currentUser()->id());
        if ($eform_type->isDraftable()) {
          $query->sort('draft', 'DESC');
        }
        $query->sort('created', 'DESC');
        $ids = $query->execute();
        $id = array_shift($ids);
        $eform_submission = $this->entityStorage()->load($id);
        if (empty($eform_submission)) {
          $eform_submission = $this->getNewSubmission($eform_type);
        }
      }
    }
    return $eform_submission;
  }

  /**
   * @return \Drupal\Core\Entity\EntityStorageInterface
   */
  protected function entityStorage() {
    return $this->entityManager()->getStorage('eform_submission');
  }

  /**
   * @param \Drupal\eform\Entity\EFormType $eform_type
   *
   * @return \Drupal\Core\Entity\EntityInterface
   */
  protected function getNewSubmission(EFormType $eform_type) {
    $eform_submission = $this->entityStorage()
      ->create(array(
        'type' => $eform_type->id(),
      ));
    return $eform_submission;
  }
  protected function getDraftSubmission(EFormType $eform_type) {
    if ($eform_type->isDraftable()) {
      $query = $this->entityStorage()->getQuery();
      $query->condition('uid', $this->currentUser()->id());
      $query->condition('draft', EFORM_DRAFT);
      // Should not be more than 1 draft.
      $query->sort('created', 'DESC');
      $ids = $query->execute();
      if ($ids) {
        $id = array_shift($ids);
        return $this->entityStorage()->load($id);
      }
    }
    return NULL;
  }
  /**
   * Constructs a NodeController object.
   *
   * @param \Drupal\Core\Entity\Query\QueryInterface $query
   *   The date formatter service..
   *
  public function __construct(DateFormatter $date_formatter, RendererInterface $renderer) {
    $this->dateFormatter = $date_formatter;
    $this->renderer = $renderer;
  }

  /**
   * {@inheritdoc}
   *
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('date.formatter'),
      $container->get('renderer')
    );
  }
   */
}
