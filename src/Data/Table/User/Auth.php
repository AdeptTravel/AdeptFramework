<?php

namespace Adept\Data\Table\User;

defined('_ADEPT_INIT') or die();

class Auth extends \Adept\Abstract\Data\Items
{
  public string $type = 'Authentications';
  public string $sort = 'user_auth';

  public int $user;
  public bool $status;
  public \DateTime $created;
  public \DateTime $created_lower;
  public \DateTime $created_upper;
}
