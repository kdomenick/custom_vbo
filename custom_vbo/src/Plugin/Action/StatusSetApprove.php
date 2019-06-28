<?php

namespace Drupal\custom_vbo\Plugin\Action;

use Drupal\views_bulk_operations\Action\ViewsBulkOperationsActionBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Set status to Accept.
 *
 * @Action(
 *   id = "custom_vbo_status_set_approve",
 *   label = @Translation("Accept"),
 *   type = ""
 * )
 */
class StatusSetApprove extends ViewsBulkOperationsActionBase {

   use StringTranslationTrait;

  /**
   * {@inheritdoc}
   * This module looks to see if an entity has a field called authstatusid.  In our database, an authstatus of 'Accepted' has an ID of 2. 
   */

  public function execute($entity = NULL) {
    if ($entity->hasField('authstatusid')) {
      $entity->setAuthStatusId(2);
      $entity->save();
      return $this->t('Slips Accepted'); //Change to this whatever message you want to display to the user

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