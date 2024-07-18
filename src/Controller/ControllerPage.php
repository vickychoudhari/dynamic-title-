<?php

namespace Drupal\dynamic_title\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use Drupal\Core\Database\Database;


/**
 * Function.
 */
class ControllerPage extends ControllerBase {
  public function PageTitle(Node $node) {
    // Get the node ID from the URL.
  $node_id = $node->id();

  // Check if the node ID is valid.
  if (!$node_id) {
    return t('Invalid node ID.');
  }

  // Query to get the title directly.
  $connection = Database::getConnection();
  $query = $connection->select('node_field_data', 'nfd');
$query->join('node__body', 'nf', 'nfd.nid = nf.entity_id');// Use 'nf' for the alias
$query->fields('nfd', ['title']);
$query->fields('nf', ['body_value']); // Use 'nf' for the alias
$query->condition('nfd.nid', $node_id);
$query->condition('nfd.type', ['article', 'page'], 'IN');
$result =$query->execute()->fetchAssoc();

  if ($result) {
    $title = $result['title'];
    $body = $result['body_value'];

    return [
      '#cache' => ['max-age' => 0],
      '#theme' => 'DynamicTitle',
      '#title' => $title,
      '#body'  => $body
    ];

  } else {
    return t('Title not found for node ID @nid.', ['@nid' => $node_id]);
  }
}

  public function NodeData(Node $node, $node_id) {
    return [
        '#markup' => "Sample Controller Page",
    ];
  }

}
