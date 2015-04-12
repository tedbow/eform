<?php

/**
 * @file
 * Contains \Drupal\eform\Entity\EFormType.
 */

namespace Drupal\eform\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;
use Drupal\Core\Entity\EntityStorageInterface;

/**
 * Defines the EForm type configuration entity.
 *
 * @ConfigEntityType(
 *   id = "eform_type",
 *   label = @Translation("EForm type"),
 *   handlers = {
 *     "access" = "Drupal\Core\Entity\EntityAccessControlHandler",
 *     "form" = {
 *       "add" = "Drupal\eform\EFormTypeForm",
 *       "edit" = "Drupal\eform\EFormTypeForm",
 *     },
 *     "list_builder" = "Drupal\eform\EFormTypeListBuilder",
 *   },
 *   admin_permission = "administer eform types",
 *   config_prefix = "type",
 *   bundle_of = "eform_submission",
 *   entity_keys = {
 *     "id" = "type",
 *     "label" = "name",
 *   },
 *   links = {
 *     "delete-form" = "eform.eformtype_delete",
 *     "edit-form" = "eform.eformtype_edit",
 *     "collection" = "/admin/structure/eform_types",
 *   }
 * )
 */
class EFormType extends ConfigEntityBundleBase {

  /**
   * Closed status for a form.
   */
  const STATUS_CLOSED = 'EFORM_CLOSED';
  /**
   * Open status for a form.
   */
  const STATUS_OPEN = 'EFORM_OPEN';

  const RESUBMIT_ACTION_OLD = 'EFORM_RESUBMIT_OLD';
  const RESUBMIT_ACTION_NEW = 'EFORM_RESUBMIT_NEW';
  const RESUBMIT_ACTION_DISALLOW = 'EFORM_RESUBMIT_DISALLOW';
  const RESUBMIT_ACTION_CONFIRM = 'EFORM_RESUBMIT_CONFIRM';
  /**
   * The machine name of this eform type.
   *
   * @var string
   *
   * @todo Rename to $id.
   */
  public $type;

  public $preview_page = 0;

  /**
   * The human-readable name of the eform type.
   *
   * @var string
   *
   * @todo Rename to $label.
   */
  public $name;

  /**
   * The title to use for the form.
   *
   * @var string

   */
  public $form_title = 'Title';

  /**
   * A brief description of this eform type.
   *
   * @var string
   */
  public $description;

  /**
   * Help information shown to the user when creating a EForm of this type.
   *
   * @var string
   */
  public $help;

  /**
   * Current status of the form. Currently only closed or open.
   * @var string;
   */
  public $form_status;
  /**
   * Roles @todo text
   * @var array;
   */
  public $roles;

  /**
   * @var string;
   */
  public $resubmit_action;

  /**
   * Module-specific settings for this eform type, keyed by module name.
   *
   * @var array
   *
   * @todo Pluginify.
   */
  public $settings = array();

  /**
   * {@inheritdoc}
   */
  public function id() {
    return $this->type;
  }

  /**
   * {@inheritdoc}
   */
  public function getModuleSettings($module) {
    if (isset($this->settings[$module]) && is_array($this->settings[$module])) {
      return $this->settings[$module];
    }
    return array();
  }

  /**
   * {@inheritdoc}
   */
  public function isLocked() {
    $locked = \Drupal::state()->get('eform.type.locked');
    return isset($locked[$this->id()]) ? $locked[$this->id()] : FALSE;
  }
  /**
   * {@inheritdoc}
   *
  public function postSave(EntityStorageControllerInterface $storage_controller, $update = TRUE) {
    parent::postSave($storage_controller, $update);

    if (!$update) {
      // Clear the eform type cache, so the new type appears.
      \Drupal::cache()->deleteTags(array('eform_types' => TRUE));

      entity_invoke_bundle_hook('create', 'eform', $this->id());

      // Unless disabled, automatically create a Body field for new eform types.
      if ($this->get('create_body')) {
        $label = $this->get('create_body_label');
        eform_add_body_field($this, $label);
      }
    }
    elseif ($this->getOriginalID() != $this->id()) {
      // Clear the eform type cache to reflect the rename.
      \Drupal::cache()->deleteTags(array('eform_types' => TRUE));

      $update_count = eform_type_update_eforms($this->getOriginalID(), $this->id());
      if ($update_count) {
        drupal_set_message(format_plural($update_count,
          'Changed the eform type of 1 post from %old-type to %type.',
          'Changed the eform type of @count posts from %old-type to %type.',
          array(
            '%old-type' => $this->getOriginalID(),
            '%type' => $this->id(),
          )));
      }
      entity_invoke_bundle_hook('rename', 'eform', $this->getOriginalID(), $this->id());
    }
    else {
      // Invalidate the cache tag of the updated eform type only.
      cache()->invalidateTags(array('eform_type' => $this->id()));
    }
  }

  **
   * {@inheritdoc}
   *
  public static function postDelete(EntityStorageControllerInterface $storage_controller, array $entities) {
    parent::postDelete($storage_controller, $entities);

    // Clear the eform type cache to reflect the removal.
    $storage_controller->resetCache(array_keys($entities));
    foreach ($entities as $entity) {
      entity_invoke_bundle_hook('delete', 'eform', $entity->id());
    }
  }
*/
}

