<?php

namespace Drupal\custom_vbo\Plugin\Action;

use Drupal\views_bulk_operations\Action\ViewsBulkOperationsActionBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Set status to Process.
 *
 * @Action(
 *   id = "custom_vbo_status_set_process",
 *   label = @Translation("Processed"),
 *   type = ""
 * )
 */
class StatusSetProcess extends ViewsBulkOperationsActionBase {

   use StringTranslationTrait;

  /**
   * {@inheritdoc}
   * This module looks to see if an entity has a field called authstatusid.  In our database, an authstatus of 'Processed' has an ID of 4. 
   */

  public function execute($entity = NULL) {
    if ($entity->hasField('authstatusid')) {
      $entity->setAuthStatusId(4);
      $entity->save();
      return $this->t('Slips Processed');//Change to this whatever message you want to display to the user 
    }
  }

  /**
   * {@inheritdoc}
   */
  public function access($object, AccountInterface $account = NULL, $return_as_object = FALSE) {
    if ($object->getEntityType() === 'node') {
      $access = $object->access('update', $account, TRUE)
        ->andIf($object->status->access('edit', $account, TRUE));
      return $return_as_object ? $access : $access->isAllowed();
    }

    // Other entity types may have different
    // access methods and properties.
    return TRUE;
  }

}