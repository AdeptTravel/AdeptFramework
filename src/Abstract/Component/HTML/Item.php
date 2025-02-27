<?php

namespace Adept\Abstract\Component\HTML;

defined('_ADEPT_INIT') or die('No Access'); // Prevent direct access to the file

use \Adept\Application;

abstract class Item extends \Adept\Abstract\Component\HTML
{
  /**
   * Constructor for the Item class. Initializes the component, retrieves GET and POST data, and handles saving actions.
   */
  public function __construct()
  {
    // Call the parent constructor
    parent::__construct();

    // Get the singleton instance of the application
    $app = Application::getInstance();

    // Reference to GET and POST data from the session
    $get  = $app->session->request->data->get;
    $post = $app->session->request->data->post;

    $idPost = $post->getInt('id', 0);
    $idGet  = $get->getInt('id', 0);

    // Check if GET and POST 'id' parameters are set and match; if not, halt execution
    if ($idGet > 0 && $idPost > 0 && $idGet != $idPost) {
      // If IDs from GET and POST don't match, block execution to prevent tampering
      die("You're being a naughty."); // Terminates execution due to potential tampering
    }

    // Retrieve the action string from POST data (default empty)
    $action = $post->getString('action', '');

    // Determine whether the ID is coming from POST or GET data
    $id = (!empty($action))
      ? $idPost // If there's an action, use POST ID
      : $idGet; // Otherwise, use GET ID

    // TODO: Add this into the status
    if (!$app->session->request->route->allowGet) {
      echo '<p>ERROR: GET is not allowed in the route.</p>';
      $this->status->addError('GET Not Allowed', ' The route is currently blocking GET data which is required for this component to function.  Please go to Routes->Edit for this route and add Allow Get.');
    }

    if (!$app->session->request->route->allowPost) {
      echo '<p>ERROR: POST is not allowed in the route.</p>';
      $this->status->addError('POST Not Allowed', ' The route is currently blocking POST data which is required for this component to function.  Please go to Routes->Edit for this route and add Allow Post.');
    }

    // Retrieve the item object by ID
    $item = $this->getItem($id);

    // Check if the action contains 'save'
    if (strpos($action, 'save') !== false) {

      // Load data from POST into the item object
      $item->loadFromPost($post);

      // If an ID is provided, set it on the item object
      //if ($id > 0) {
      //  $item->id = $id;
      //}

      // Attempt to save the item
      if ($item->save()) {
        // On success, add a success message to the status
        $this->status->addSuccess('Success', 'The data was successfully saved.');
      } else {
        // If there are errors, add each error to the status
        for ($i = 0; $i < count($item->error); $i++) {
          $this->status->addError('Error', print_r($item->error[$i], true));
        }
      }
    }

    // If an action exists and saving was successful
    if (!empty($action) && !empty($this->status->success)) {
      // Get the current request path
      $path = '/' . $app->session->request->url->path;
      // Remove the last part of the path (i.e., anything after the last '/')
      $path = substr($path, 0, strrpos($path, '/'));


      //die($path);
      // Redirect based on the action type
      if ($action == 'close' || $action == 'saveclose') {
        // Redirect to the base path when closing or saving and closing
        if (isset($_COOKIE['urlQueryData'])) {
          $urlData = (array)json_decode($_COOKIE['urlQueryData']);

          if (!empty($urlData[$path])) {
            $path .= $urlData[$path];
          }
        }

        $app->session->request->redirect($path);
      } else if ($action == 'savenew' || $action == 'savecopy') {
        // Redirect to the 'edit' page for creating new or copying items
        $app->session->request->redirect($path . '/edit');
      } else if ($action == 'save' && $id == 0) {
        $app->session->request->redirect($path . '/edit?id=' . $item->id);
      }
    }
  }

  /**
   * Abstract method that must be implemented in child classes to retrieve an item by its ID.
   *
   * @param int $id The ID of the item to retrieve.
   * @return \Adept\Abstract\Data\Item The item object.
   */
  abstract function getItem(int $id): \Adept\Abstract\Data\Item;
}
