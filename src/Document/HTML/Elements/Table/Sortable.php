<?php

namespace Adept\Document\HTML\Elements\Table;

defined('_ADEPT_INIT') or die();

use \Adept\Application;
use \Adept\Document\HTML\Elements\A;
use \Adept\Document\HTML\Elements\Caption;
use \Adept\Document\HTML\Elements\I;
use \Adept\Document\HTML\Elements\Span;
use \Adept\Document\HTML\Elements\Table;
use \Adept\Document\HTML\Elements\Tbody;
use \Adept\Document\HTML\Elements\Thead;
use \Adept\Document\HTML\Elements\Tfoot;
use \Adept\Document\HTML\Elements\Tr;
use \Adept\Document\HTML\Elements\Th;
use \Adept\Document\HTML\Elements\Td;
use \Adept\Document\HTML\Elements\Colgroup;
use \Adept\Document\HTML\Elements\Col;
use Adept\Document\HTML\Elements\Input\Checkbox;
use Adept\Document\HTML\Elements\Input\Hidden;

/**
 * Undocumented class
 */
class Sortable extends Table
{
	public array $data = [];

	/**
	 * Undocumented variable
	 *
	 * @var array
	 */
	public array $header = [];

	/**
	 * Undocumented variable
	 *
	 * @var string
	 */
	public string $caption;

	/**
	 * Undocumented variable
	 *
	 * @var bool
	 */
	public bool $recursive = false;

	/**
	 * Undocumented variable
	 *
	 * @var bool
	 */
	public bool $reorder = false;

	/**
	 * Include a checkbox to select an item in the table
	 *
	 * @var bool
	 */
	public bool $select = false;

	protected string $path;

	protected string $sort;

	protected string $sortDir;

	protected Colgroup $colgroup;

	protected Thead $thead;

	/**
	 * Undocumented function
	 *
	 * @param  \Adept\Application $app
	 * @param  array              $attr
	 * @param  array              $head
	 * @param  array              $data
	 */
	public function __construct(array $attr = [], array $data = [])
	{
		$app  = Application::getInstance();
		$head = $app->html->head;

		$this->data    = $data;
		$this->path    = '/' . $app->session->request->url->path;
		$this->sort    = $app->session->request->data->get->getString('sort', '');
		$this->sortDir = $app->session->request->data->get->getString('dir', 'asc');

		parent::__construct($attr, []);

		$head->css->addFile('table.css');
		$head->javascript->addFile('form.ajax.js');
		//$head->javascript->addFile('form.table.js');
		$head->javascript->addFile('table.toggle.js');

		if ($this->reorder) {
			$head->javascript->addFile('table.sortable.js');
		}
	}

	public function addCol(string $column, string $title, array $css = [], bool $edit = false)
	{
		$fa = [];

		$this->header[] = (object)[
			'column' 	=> $column,
			'title' 	=> $title,
			'edit' 		=> $edit,
			'css' 		=> $css
		];
	}

	public function getBuffer(): string
	{
		$app  = Application::getInstance();
		$get = $app->session->request->data->get;
		$url = $app->session->request->url;

		$path = '/' . $url->path;
		$sort = $get->getString('sort', '');
		$dir  = $get->getString('dir', 'asc');

		$parts = $get->getArray();

		foreach (['dir', 'sort'] as $v) {
			if (array_key_exists($v, $parts)) {
				unset($parts[$v]);
			}
		}

		$query = http_build_query($parts);

		if ($this->reorder) {

			$this->header = array_merge([(object)[
				'column' 	=> 'order',
				'title' 	=> '',
				'edit' 		=> false,
				'css' 		=> ['fa-solid', 'fa-grip-vertical', 'grab']
			]], $this->header);
		}

		if ($this->select) {

			$this->header = array_merge([(object)[
				'column' 	=> 'select',
				'title' 	=> '',
				'edit' 		=> false,
				'css' 		=> []
			]], $this->header);
		}

		if (!empty($this->header)) {
			$thead = new Thead();
			$colgroup = new Colgroup();

			for ($i = 0; $i < count($this->header); $i++) {
				$col   = &$this->header[$i];

				$href  = $path . '?' . ((!empty($query)) ? $query . '&' : '');
				$href .= 'sort=' . $col->column;
				$href .= '&dir=' . (($sort == $col->column && $dir == 'asc') ? 'desc' : 'asc');
				$html  = $col->title;

				if ($sort == $col->column) {
					$html .=  ($dir == 'asc') ? '&nbsp;<i class="fa-solid fa-caret-down"></i>' : '&nbsp;<i class="fa-solid fa-caret-up"></i>';
				}

				$thead->children[] = new Th([], [
					new A([
						'href' => $href,
						'html' => $html
					])
				]);

				$colgroup->children[] = new Col([
					'css' => (in_array('fa-solid', $col->css)) ? ['icon'] : ''
				]);
			}

			$this->children[] = $thead;
			$this->children[] = $colgroup;

			$tbody = new Tbody();

			if ($this->reorder) {
				$tbody->css[] = 'reorder';
			}

			$tbody->children = $this->getRows();
		}

		$this->children[] = $tbody;

		return parent::getBuffer();
	}

	public function getRows(int $parent = 0, int $index = 0): array
	{
		$rows = [];

		for ($i = 0; $i < count($this->data); $i++) {
			$rows[] = $this->getRow($this->data[$i]);
		}

		return $rows;
	}

	public function getRow(object $row): Tr
	{
		$tr   = new Tr();
		$path = '/' .  Application::getInstance()->session->request->url->path;

		if ($this->reorder || $this->select) {

			$tr->draggable = ($this->reorder);

			if (isset($row->id)) {
				$tr->data['id'] = $row->id;

				if ($this->reorder && isset($row->parent)) {
					$tr->data['group'] = $row->parent;
				}
			}
		}

		for ($i = 0; $i < count($this->header); $i++) {
			$col   = &$this->header[$i];
			$index = $col->column;
			$td    = new Td();

			for ($k = 0; $k < count($col->css); $k++) {
				if (substr($col->css[$k], 0, 3) != 'fa-') {
					$td->css[] = $col->css[$k];
				}
			}

			if ($col->column == 'id') {
				$td->scope = 'row';
				$td->html = '<input type="hidden" name="id" value="' . $row->$index . '">' . $row->$index;
			} else if (in_array('fa-solid', $col->css)) {
				$td->html = '<i class="' . $col->column . ' ' . implode(' ', $col->css) . ' ' . (($row->$index == 1) ? 'on' : 'off') . '">';
			} else {
				if ($col->edit) {

					$text = $row->$index;

					if (isset($row->level)) {
						$text = ' ' . str_repeat("-", $row->level) . ' ' . $text;
					}

					$td->children[] = new A([
						'href' => $path . '/edit?id=' . $row->id,
						'text' => $text
					]);
				} else if ($col->column == 'select') {
					$td->children[] = new Checkbox([
						'name' => 'select',
						'value' => $row->id
					]);
				} else {
					//die('<pre>' . print_r($col, true));
					//die('<pre>' . print_r($row, true));
					$td->html = $row->$index;
				}
			}

			$tr->children[] = $td;
		}

		return $tr;
	}
}
