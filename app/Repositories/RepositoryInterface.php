<?php

namespace App\Repositories;

interface RepositoryInterface
{
    /**
     * Get all
     * @param array $with
     * @return mixed
     */
    public function getAll(array $with = array());
    /**
     * make
     * @param array $with
     * @return mixed
     */
    public function make(array $with = array());
    /**
     * Get one
     * @param $id
     * @return mixed
     */
    public function find($id);
    /**
     * Create
     * @param array $attributes
     * @return mixed
     */
    public function create(array $attributes);
    /**
     * Update
     * @param $id
     * @param array $attributes
     * @return mixed
     */
    public function update($id, array $attributes);
    /**
     * Delete
     * @param $id
     * @return mixed
     */
    public function delete($id);
    /**
     *pagination
     */
    public function paginate($limit = null);

    /**
     * Eager Loading Pagination
     */
    public function pagination($limit = null, array $with = array());

    public function updateOrCreate(array $attributes, array $values = []);

    public function updateAll(array $conditions, array $attributes);

    public function deleteAll(array $conditions);

    public function save(array $options);

    public function insert(array $attributes);

    public function beginTransaction();

    public function commit();

    public function rollback();

    public function insertOrIgnore(array $values);

    public function disableQueryLog();
    public function findAll(array $conditions, array $attributes);

    public function findAllForUpdate(array $conditions, array $attributes);
}
