<?php

/**
 * @file
 * Definition of Drupal\eform\Entity\EFormSubmission.
 */

namespace Drupal\eform\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Language\LanguageInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\user\UserInterface;
use Drupal\eform\EFormTypeForm;
use Drupal\Core\Entity\ContentEntityInterface;

/**
 * Defines the eform entity class.
 *
 *
 * @ContentEntityType(
 *   id = "eform_submission",
 *   label = @Translation("EForm Submission"),
 *   bundle_label = @Translation("EForm type"),
 *   handlers = {
 *     "storage" = "Drupal\Core\Entity\Sql\SqlContentEntityStorage",
 *     "storage_schema" = "Drupal\Core\Entity\Sql\SqlContentEntityStorageSchema",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "access" = "Drupal\Core\Entity\EntityAccessControlHandler",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "form" = {
 *       "default" = "Drupal\eform\EFormSubmissionForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm",
 *       "edit" = "Drupal\eform\EFormSubmissionForm"
 *     },
 *
 *   },
 *   base_table = "eform_submission",
 *   data_table = "eform_submission_field_data",
 *   revision_table = "eform_submission_revision",
 *   revision_data_table = "eform_submission_field_revision",
 *   uri_callback = "eform_submission_uri",
 *   fieldable = TRUE,
 *   translatable = TRUE,
 *   entity_keys = {
 *     "id" = "eid",
 *     "revision" = "vid",
 *     "bundle" = "type",
 *     "label" = "title",
 *     "uuid" = "uuid",
 *     "langcode" = "langcode"
 *   },
 *   bundle_entity_type = "eform_type",
 *   field_ui_base_route = "entity.eform_type.edit_form",
 *   permission_granularity = "bundle",
 *   links = {
 *     "canonical" = "eform_submission.view",
 *     "edit-form" = "eform_submission.page_edit",
 *     "version-history" = "eform_submission.revision_overview"
 *   }
 * )
 */
class EFormSubmission extends ContentEntityBase implements ContentEntityInterface {



  /**
   * {@inheritdoc}
   */
  public function getType() {
    return $this->bundle();
  }

  /**
   * @return \Drupal\eform\Entity\EFormType
   *  Loaded EFormType
   */
  public function getEFormType() {
    return EFormType::load($this->bundle());
  }


  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }


  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getChangedTime() {
    return $this->get('changed')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function getAuthor() {
    return $this->get('uid')->entity;
  }

  public function getOwner() {
    return $this->get('uid')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('uid')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('uid', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('uid', $account->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getRevisionCreationTime() {
    return $this->get('revision_timestamp')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setRevisionCreationTime($timestamp) {
    $this->set('revision_timestamp', $timestamp);
    return $this;
  }
  /**
   * {@inheritdoc}
   */
  public function getRevisionAuthor() {
    return $this->get('revision_uid')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function setRevisionAuthorId($uid) {
    $this->set('revision_uid', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields['eid'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('EForm Submission ID'))
      ->setDescription(t('The eform submission ID.'))
      ->setReadOnly(TRUE);
    $fields['uuid'] = BaseFieldDefinition::create('uuid')
      ->setLabel(t('UUID'))
      ->setDescription(t('The EForm Submission UUID.'))
      ->setReadOnly(TRUE);
    $fields['vid'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Revision ID'))
      ->setDescription(t('The EForm Submission revision ID.'))
      ->setReadOnly(TRUE);
    $fields['type'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Type'))
      ->setSetting('target_type', 'eform_type')
      ->setReadOnly(TRUE);
    $fields['uid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Submitter'))
      ->setDescription(t('The user that who submitted the EForm.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'user')
      ->setTranslatable(TRUE);
    $fields['draft'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Draft status'))
      ->setDescription(t('A boolean indicating whether the EForm Submission is a draft.'))
      ->setRevisionable(TRUE)
      ->setTranslatable(TRUE)
      ->setDefaultValue(FALSE);
    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the EForm was submitted.'))
      ->setRevisionable(TRUE)
      ->setTranslatable(TRUE);

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the EForm was last edited.'))
      ->setRevisionable(TRUE)
      ->setTranslatable(TRUE);


    $fields['revision_timestamp'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Revision timestamp'))
      ->setDescription(t('The time that the current revision was created.'))
      ->setQueryable(FALSE)
      ->setRevisionable(TRUE);

    $fields['revision_uid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Revision user ID'))
      ->setDescription(t('The user ID of the submitter of the current revision.'))
      ->setSetting('target_type', 'user')
      ->setQueryable(FALSE)
      ->setRevisionable(TRUE);

    $fields['revision_log'] = BaseFieldDefinition::create('string_long')
      ->setLabel(t('Revision log message'))
      ->setDescription(t('The log entry explaining the changes in this revision.'))
      ->setRevisionable(TRUE)
      ->setTranslatable(TRUE);

    $fields['langcode'] = BaseFieldDefinition::create('language')
      ->setLabel(t('Language'))
      ->setDescription(t('The EForm Submission language code.'))
      ->setTranslatable(TRUE)
      ->setRevisionable(TRUE)
      ->setDisplayOptions('view', array(
        'type' => 'hidden',
      ))
      ->setDisplayOptions('form', array(
        'type' => 'language_select',
        'weight' => 2,
      ));
    return $fields;
  }
  /**
   * {@inheritdoc}
   */
  public function preSave(EntityStorageInterface $storage) {
    parent::preSave($storage);

    // If no owner has been set explicitly, make the current user the owner.
    if (!$this->getOwner()) {
      // @todo Copy from Node.php should dependency inject be used to get current user?
      $this->setOwnerId(\Drupal::currentUser()->id());
    }
    // If no revision author has been set explicitly, make the node owner the
    // revision author.
    if (!$this->getRevisionAuthor()) {
      $this->setRevisionAuthorId($this->getOwnerId());
    }
  }

  /**
   * @param $draft
   *
   * @return bool
   */
  public function isDraft() {
    return (bool) $this->get('draft')->value;
  }

  /**
   * @param $draft
   *
   * @return \Drupal\eform\Entity\EFormSubmission
   *   The called EForm Submission entity.
   */
  public function setDraft($draft) {
    $this->set('draft', $draft ? EFORM_DRAFT : EFORM_NOT_DRAFT);
    return $this;
  }
  public function isSubmitted() {
    return !$this->isDraft()  && !$this->isNew();
  }



}
