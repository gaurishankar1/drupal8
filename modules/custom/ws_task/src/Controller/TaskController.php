<?php
namespace Drupal\ws_task\Controller;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Routing\RouteMatch;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Cmf\Component\Routing\RouteObjectInterface;
use Drupal\ws_task\Model\TaskRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * An Wishlist controller.
 */
class TaskController extends ControllerBase {
  /**
   * The repository for our specialized queries.
   *
   * @var \Drupal\ws_task\TaskRepository
   */
  protected $repository;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $controller = new static($container->get('ws_task.repository'));
    $controller->setStringTranslation($container->get('string_translation'));
    return $controller;
  }

  /**
   * Construct a new controller.
   *
   * @param \Drupal\ws_task\TaskRepository $repository
   *   The repository service.
   */
  public function __construct(TaskRepository $repository) {
    $this->repository = $repository;
  }  


  /**
   * {@inheritdoc}
   */
  // Task dashboard links
  public function dashboard() {
    return array(
      '#theme' => 'task_dashboard',
    );
  }  

  // Listing of tabular task
  public function tabular_tasks_list() {
    $rows = [];
    $headers = [
      $this->t('Task Name'),
      $this->t('Assignee'),
      $this->t('Priority'),
      $this->t('Start Date'),
      $this->t('End Date'),
      $this->t('Status'),
    ];



    $tasks = $this->repository->loadtaskListing();
    /*print "<pre>";
    print_r($tasks);
    print "</pre>";*/
    foreach ($tasks as $task) {
      // Sanitize each entry.
      $rows[] = array_map('Drupal\Component\Utility\Html::escape', (array) $task);
    }
    $content['table'] = [
      '#type' => 'table',
      '#header' => $headers,
      '#rows' => $rows,
      '#empty' => $this->t('No task available.'),
    ];
    // Don't cache this page.
    $content['#cache']['max-age'] = 0;

    return $content;


    /*
		$array = array(
			"task_name" => "Rahul Task",
			"task_start_date" => '02-Dec-2019',
			"task_end_date" => '02-Oct-2020',
			"task_priority" => 'High',
			"task_assignee" => 'Rahul',
			"task_status" => 'Active',
		);		
		$taskLists[] = (object) $array; 
    return array(
      '#theme' => 'tabular_task',
      '#task_lists' => $taskLists,
    );*/
  }

  // Add new task
  public function add_task() {

  }
}