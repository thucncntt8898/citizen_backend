<?php
namespace App\Repositories;

use App\Repositories\RepositoryInterface;
use Illuminate\Support\Facades\DB;

abstract class Repository implements RepositoryInterface
{
    /**
     * @var RepositoryInterface|\Illuminate\Database\Eloquent\Model
     */
    protected $_model;

    public function __construct()
    {
        $this->setModel();
    }

    /**
     * get model
     * @return string
     */
    abstract public function getModel();

    /**
     * Set model
     */
    public function setModel()
    {
        $this->_model = app()->make(
            $this->getModel()
        );
    }

    /**
     * make
     * @param array $with
     * @return mixed
     */
    public function make(array $with = array() )
    {
        $result = $this->_model->with($with);
        return $result;
    }

    /*
    * Get All
    * @return \Illuminate\Database\Eloquent\Collection|static[]
    */
    public function getAll( array $with = array() )
    {
        return $this->make($with)->orderBy('created_at', 'desc')->get();
    }

    /**
     * Get one
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        $result = $this->_model->find($id);
        return $result;
    }

    /**
     * Create
     * @param array $attributes
     * @return mixed
     */
    public function create(array $attributes)
    {
        return $this->_model->create($attributes);
    }

    /**
     * Update
     * @param $id
     * @param array $attributes
     * @return bool|mixed
     */
    public function update($id, array $attributes)
    {

        $result = $this->find($id);
        if($result) {
            $result->update($attributes);
            return $result;
        }
        return false;
    }

    /**
     * Delete
     *
     * @param $id
     * @return bool
     */
    public function delete($id)
    {
        $result = $this->find($id);
        if($result) {
            $result->delete();
            return true;
        }
        return false;
    }

    /**
     * pagination
     * @param $limit
     * @return mixed
     */
    public function paginate($limit = null)
    {
        $result =  $this->_model->orderBy('created_at', 'desc')->paginate($limit);
        return $result;
    }

    /**
     * Eager Loading Pagination
     * @param $limit
     * @return mixed
     */
    public function pagination($limit = null, array $with = array())
    {
        $result =  $this->make($with)->orderBy('created_at', 'desc')->paginate($limit);
        return $result;
    }

    public function updateOrCreate(array $attributes, array $values = [])
    {
        return $this->_model->updateOrCreate($attributes, $values);
    }

    public function updateAll(array $conditions, array $attributes)
    {
        $query = $this->_model;
        $allow = false;
        foreach ($conditions as $field => $values) {
            $compareArr = explode(' ', trim($field));
            if (count($compareArr) == 2) {
                $query = $query->where($compareArr[0], $compareArr[1], $values);
                $allow = true;
            } elseif (count($compareArr) == 1) {
                if (is_array($values)) {
                    $query = $query->whereIn($field, $values);
                } else {
                    $query = $query->where($field, $values);
                }
                $allow = true;
            }
        }

        if (!$allow) return false;

        return $query->update($attributes);
    }

    public function deleteAll(array $conditions)
    {
        $query = $this->_model;
        $allow = false;
        foreach ($conditions as $field => $values) {
            $compareArr = explode(' ', trim($field));
            if (count($compareArr) == 2) {
                $query = $query->where($compareArr[0], $compareArr[1], $values);
                $allow = true;
            } elseif (count($compareArr) == 1) {
                if (is_array($values)) {
                    $query = $query->whereIn($field, $values);
                } else {
                    $query = $query->where($field, $values);
                }
                $allow = true;
            }
        }

        if (!$allow) return false;

        return $query->delete();
    }

    public function save(array $options = [])
    {
        return $this->_model->save($options);
    }

    /**
     * Create
     * @param array $attributes
     * @return mixed
     */
    public function insert(array $attributes)
    {
        return $this->_model->insert($attributes);
    }

    public function getEloquentSqlWithBindings($query)
    {
        return vsprintf(str_replace('?', '%s', $query->toSql()), collect($query->getBindings())->map(function ($binding) {
            return is_numeric($binding) ? $binding : "'{$binding}'";
        })->toArray());
    }

    public function beginTransaction()
    {
        $db = DB::connection($this->_model->getConnectionName());
        $db->beginTransaction();
    }

    public function commit(){
        $db = DB::connection($this->_model->getConnectionName());
        return $db->commit();
    }

    public function rollback(){
        return DB::rollback();
    }

    public function insertOrIgnore(array $values)
    {
        return $this->_model->insertOrIgnore($values);
    }

    public function disableQueryLog()
    {
        $db = DB::connection($this->_model->getConnectionName());
        $db->disableQueryLog();
    }

    public function get($page, $limit)
    {
        return $this->_model->orderBy('created', 'desc')->forPage($page, $limit)->get();
    }

    public function findAll(array $conditions, array $attributes = ['*'])
    {
        $query = $this->_model;
        $allow = false;
        foreach ($conditions as $field => $values) {
            $compareArr = explode(' ', trim($field));
            if (count($compareArr) == 2) {
                $query = $query->where($compareArr[0], $compareArr[1], $values);
                $allow = true;
            } elseif (count($compareArr) == 1) {
                if (is_array($values)) {
                    $query = $query->whereIn($field, $values);
                } else {
                    $query = $query->where($field, $values);
                }
                $allow = true;
            }
        }

        if (!$allow) return [];

        return $query->select($attributes)->get()->toArray();
    }

    public function findAllForUpdate(array $conditions, array $attributes = ['*'])
    {
        $query = $this->_model;
        $allow = false;
        foreach ($conditions as $field => $values) {
            $compareArr = explode(' ', trim($field));
            if (count($compareArr) == 2) {
                $query = $query->where($compareArr[0], $compareArr[1], $values);
                $allow = true;
            } elseif (count($compareArr) == 1) {
                if (is_array($values)) {
                    $query = $query->whereIn($field, $values);
                } else {
                    $query = $query->where($field, $values);
                }
                $allow = true;
            }
        }

        if (!$allow) return [];

        return $query->select($attributes)->lockForUpdate()->get()->toArray();
    }
}
