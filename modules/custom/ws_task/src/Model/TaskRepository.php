<?php
namespace Drupal\ws_task\Model;

use Drupal\Core\Database\Connection;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Messenger\MessengerTrait;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\StringTranslation\TranslationInterface;
/*
 * @ingroup ws_task
 */
class TaskRepository {
  use MessengerTrait;
  use StringTranslationTrait;
  /**
   * The database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $connection;

  /**
   * Construct a repository object.
   *
   * @param \Drupal\Core\Database\Connection $connection
   *   The database connection.
   * @param \Drupal\Core\StringTranslation\TranslationInterface $translation
   *   The translation service.
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   *   The messenger service.
   */
  public function __construct(Connection $connection, TranslationInterface $translation, MessengerInterface $messenger) {
    $this->connection = $connection;
    $this->setStringTranslation($translation);
    $this->setMessenger($messenger);
  }

  /**
   * Save an entry in the database.
   *
   * Exception handling is shown in this example. It could be simplified
   * without the try/catch blocks, but since an insert will throw an exception
   * and terminate your application if the exception is not handled, it is best
   * to employ try/catch.
   *
   * @param array $entry
   *   An array containing all the fields of the database record.
   *
   * @return int
   *   The number of updated rows.
   *
   * @throws \Exception
   *   When the database insert fails.
   */
  // Insert Task
  public function insertTask(array $entry) {
    try {
      $return_value = $this->connection->insert('tasks')
        ->fields($entry)
        ->execute();
    }
    catch (\Exception $e) {
      $this->messenger()->addMessage(t('Insert failed. Message = %message', [
        '%message' => $e->getMessage(),
      ]), 'error');
    }
    return $return_value ?? NULL;
  }
  // Insert Assignees of Task
  public function inserttaskAssignees(array $entry) {
    try {
      $return_value = $this->connection->insert('task_assignees')
        ->fields($entry)
        ->execute();
    }
    catch (\Exception $e) {
      $this->messenger()->addMessage(t('Insert failed. Message = %message', [
        '%message' => $e->getMessage(),
      ]), 'error');
    }
    return $return_value ?? NULL;
  }  
  // Fetch User List
  public function selectuserList() {
    $con = $this->connection;
    $query = $con->select('users_field_data', 'u');
    $query->fields('u',array('uid','name'));
    $query->condition('u.uid', 0, '<>');
    $query = $query->execute();
    $userlists = $query->fetchAll(\PDO::FETCH_OBJ);
    $userdata = [];
    foreach ($userlists as $ulist) {
      $userdata[$ulist->uid] = $ulist->name;
    }
    return $userdata;
  }  
  // Fetch task List
  public function loadtaskListing() {
    $select = $this->connection->select('tasks', 't');
    $select->join('task_assignees', 'ta', 't.id = ta.task_id');
    $select->addField('t','task_title');
    $select->addField('ta', 'assignees');
    $select->addField('t','priority');    
    $select->addField('t','start_date');
    $select->addField('t','end_date');
    $select->addField('t','task_status');
    $entries = $select->execute()->fetchAll(\PDO::FETCH_ASSOC);
    return $entries;
  }  
} 