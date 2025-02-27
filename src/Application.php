<?php

namespace Adept;

defined('_ADEPT_INIT') or die();

use DI\ContainerBuilder;
use Adept\Application\Configuration;
use Adept\Application\Database;
use Adept\Application\Debug;
use Adept\Application\Log;
use Adept\Application\Session;
use Adept\Application\Email;

class Application
{
  /**
   * The PHP-DI container instance.
   */
  protected \DI\Container $container;

  /**
   * The document object.
   */
  protected object $document;

  /**
   * Application status.
   */
  protected string $status = 'Allow';

  /**
   * Constructor.
   *
   * @param array $config Configuration array.
   *
   * @throws \RuntimeException on configuration or security failures.
   */
  public function __construct(array $config)
  {
    // Build the container using PHP-DI's ContainerBuilder.
    $builder = new ContainerBuilder();
    $builder->addDefinitions([
      // Configuration: initialized with the provided config array.
      'config' => function () use ($config) {
        return new Configuration($config);
      },
      // Debug and Log objects.
      'debug' => \DI\create(Debug::class),
      'log'   => \DI\create(Log::class),
      // Database depends on configuration.
      'db'    => \DI\create(Database::class)
        ->constructor(\DI\get('config')),
      // Session is created without dependencies (adjust as needed).
      'session' => \DI\create(Session::class),
      // Email is conditionally created based on session data.
      'email' => function (\DI\Container $c) {
        $session = $c->get('session');
        if ($session->request->route->allowEmail) {
          return new Email($c->get('config'));
        }
        return null;
      },
    ]);

    $this->container = $builder->build();

    // Load configuration overrides.
    $this->container->get('config')->load();

    // Run security and initialization routines.
    $this->validateHost();
    $this->checkBlockingConditions();
    $this->initializeDocument();
  }

  /**
   * Validates the current host against allowed hosts in configuration.
   *
   * @throws \RuntimeException if the host is not permitted.
   */
  protected function validateHost(): void
  {
    $config = $this->container->get('config');
    $host = filter_var($_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'], FILTER_SANITIZE_URL);
    $allowedHosts = $config->getArray('site.host');
    if (!in_array($host, $allowedHosts, true)) {
      http_response_code(400);
      throw new \RuntimeException("The URL '$host' is not registered.");
    }
  }

  /**
   * Checks various blocking conditions based on session and request data.
   *
   * @throws \RuntimeException if any block condition is met.
   */
  protected function checkBlockingConditions(): void
  {
    $session = $this->container->get('session');
    $req = $session->request;
    if (
      $session->status === 'Block' ||
      $req->ipAddress->status === 'Block' ||
      $req->route->status === 'Block' ||
      $req->url->status === 'Block' ||
      $req->useragent->status === 'Block'
    ) {
      $this->status = 'Block';
      http_response_code(403);
      throw new \RuntimeException("403 - Forbidden");
    }
  }

  /**
   * Initializes the document object based on the current request.
   *
   * @throws \RuntimeException if the document type is unsupported.
   */
  protected function initializeDocument(): void
  {
    $session = $this->container->get('session');
    $type = $session->request->url->type;
    $documentClass = "\\Adept\\Document\\" . ucfirst($type);

    if (!class_exists($documentClass)) {
      throw new \RuntimeException("Document type '$documentClass' does not exist.");
    }

    $this->document = new $documentClass();

    if (!method_exists($this->document, 'loadComponent')) {
      throw new \RuntimeException("Document class '$documentClass' must implement loadComponent().");
    }

    $this->document->loadComponent();
  }

  /**
   * Renders the document's buffered output.
   */
  public function render(): void
  {
    echo $this->document->getBuffer();
  }

  /**
   * Returns the DI container instance.
   *
   * @return \DI\Container
   */
  public function getContainer(): \DI\Container
  {
    return $this->container;
  }
}
