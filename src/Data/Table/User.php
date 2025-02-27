<?php

namespace Adept\Data\Table;

defined('_ADEPT_INIT') or die();

class User extends \Adept\Abstract\Data\Table
{
  protected string $table = 'User';

  protected array $like = ['username', 'firstname', 'lastname'];

  public string $sort = 'lastname';

  public string $username;
  public string $firstName;
  public string $middlename;
  public string $lastname;
  public string $dob;
  public string $status;
  public string $createdAt;
  public string $updatedAt;
  public string $verifiedOn;
  public string $validatedOn;

  public function getItem(int $id): \Adept\Data\Item\User
  {
    $item = new \Adept\Data\Item\User($id);
    $item->loadFromId($id);
    return $item;
  }
}
