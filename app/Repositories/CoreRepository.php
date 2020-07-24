<?php
namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;


/**
 * Class CoreRepository
 * 
 * @package App\Repositories
 * 
 * Репозиторий для работы с сущностью.
 * Может выдавать наборы данных.
 * Не может создавать или изменять сущности.
 */
abstract class CoreRepository
{
	/**
	 * @var Model
	 */
	protected $model;

	/**
	 * CoreRepository constructor.
	 */
	public function __construct()
	{
		$this->model = app($this->getModelClass());
	}

	/**
	 * @return string
	 */
	abstract protected function getModelClass();

	/**
	 * Get Eloquent model
	 * 
	 * @return Model|\Illuminate\Foundation\Application|mixed
	 */
	protected function getModel()
	{
		return clone $this->model;
	}
}