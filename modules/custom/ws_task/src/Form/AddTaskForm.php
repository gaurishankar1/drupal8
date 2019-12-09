<?php
/**
 * @file
 * Contains \Drupal\ws_task\Form\AddTaskForm.
 */
namespace Drupal\ws_task\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Form\FormInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerTrait;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\ws_task\Model\TaskRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Url;

class AddTaskForm implements FormInterface, ContainerInjectionInterface {
  use StringTranslationTrait;
  use MessengerTrait;
  
  /**
   * Our database repository service.
   *
   * @var \Drupal\ws_task\Model\TaskRepository
   */
  protected $repository;

  /**
   * The current user.
   *
   * We'll need this service in order to check if the user is logged in.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentUser;

  /**
   * {@inheritdoc}
   *
   * We'll use the ContainerInjectionInterface pattern here to inject the
   * current user and also get the string_translation service.
   */
  public static function create(ContainerInterface $container) {
    $form = new static(
      $container->get('ws_task.repository'),
      $container->get('current_user')
    );
    // The StringTranslationTrait trait manages the string translation service
    // for us. We can inject the service here.
    $form->setStringTranslation($container->get('string_translation'));
    $form->setMessenger($container->get('messenger'));
    return $form;
  }

  /**
   * Construct the new form object.
   */
  public function __construct(TaskRepository $repository, AccountProxyInterface $current_user) {
    $this->repository = $repository;
    $this->currentUser = $current_user;
  }
    
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'add_task_form';
  }


 /**
  * {@inheritdoc}
  */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = [];
    $form['parent_task'] = array(
      '#type' => 'textfield',
      '#title' => t('Parent Task'),
      '#default_value' => 0,
    );    
    $form['task_name'] = array(
      '#type' => 'textfield',
      '#title' => t('Task Name'),
      '#required' => TRUE,
      '#attributes' => array('placeholder'=>'Type task name'),
    );
   
    $userLists = $this->repository->selectuserList();
    $form['task_assignees'] = array(
      '#title' => t('Task Assignees'),
      '#type' => 'select',
      '#required' => TRUE,
      '#default_value' => 'assigned',
      '#options' => $userLists,     
    );    
    
    $form['task_priority'] = array(
      '#title' => t('Priority'),
      '#type' => 'select',
      '#required' => TRUE,
      '#default_value' => 'high',
      '#options' => array(
        1 => t('High'),
        2 => t('Medium'),
        3 => t('Low'),
      ),
    );
    $form['task_status'] = array(
      '#title' => t('Status'),
      '#type' => 'select',
      '#required' => TRUE,
      '#default_value' => 'assigned',
      '#options' => array(
        1 => t('Assigned'),
        2 => t('In Progress'),
        3 => t('Deferred'),
        4 => t('Completed'),
      ),     
    );           
    $form['start_date'] = array (
      '#type' => 'date',
      '#title' => t('Start Date'),
      '#required' => TRUE,
    );
    $form['end_date'] = array (
      '#type' => 'date',
      '#title' => t('End Date'),
      '#required' => TRUE,
    ); 
    $form['task_description'] = array(
      '#type' => 'text_format',
      '#title' => t('Task Description'),
      '#required' => TRUE,
      '#format' => 'full_html',
  );
    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Save'),
      '#button_type' => 'primary',
    );
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // Verify that the user is logged-in.
    if ($this->currentUser->isAnonymous()) {
      $form_state->setError($form['task_name'], $this->t('You must be logged in to add values to the database.'));
    }    

    if (strlen($form_state->getValue('task_name')) < 10) {
      $form_state->setErrorByName('task_name', $this->t('task_name is too short.'));
    }
  }
  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Gather the current user so the new record has ownership.
    $account = $this->currentUser;
    // Save the submitted entry.
    $task_des = $form_state->getValue('task_description');
    $task_des = $task_des['value'];
    $entry1 = [
      'parent_task' => $form_state->getValue('parent_task'),
      'task_title' => $form_state->getValue('task_name'),
      'priority' => $form_state->getValue('task_priority'),
      'task_status' => $form_state->getValue('task_status'),
      'start_date' => strtotime($form_state->getValue('start_date')),
      'end_date' => strtotime($form_state->getValue('end_date')),
      'task_description' => $task_des,
      'completion_percentage' => 0,
      'created_by' => $account->id(),
      'updated_by' => $account->id(),
      'created_at' => REQUEST_TIME,
      'updated_at' => REQUEST_TIME,
    ];
  
    $taskId = $this->repository->insertTask($entry1);
    if(!empty($taskId)) {
      $entry2 = [
        'task_id' => $taskId,
        'assignees' => $form_state->getValue('task_assignees'),
        'assigned_by' => $account->id(),
        'created_at' => REQUEST_TIME,
        'updated_at' => REQUEST_TIME,
      ];    
      $return = $this->repository->inserttaskAssignees($entry2);
    }
    if($taskId && $return) {
      $this->messenger()->addMessage(t($form_state->getValue('task_name').' has been created successfully'));
      $url = Url::fromRoute('ws_task.tabulartask');
      $form_state->setRedirectUrl($url);
    }   
  }
}