<?php

namespace Studentsmgt\Controllers;

use Studentsmgt\Models\Schools;

class SchoolsController
{
  private $model;

  public function __construct()
  {
    $this->model = new Schools();
  }

  public function index()
  {
    return $this->model->getAll();
  }

  public function show()
  {
    $id = intval($_POST['id'] ?? 0);
    return $id > 0 ? $this->model->getById($id) : ['error' => 'Invalid ID'];
  }

  public function store()
  {
    $name = ucwords(strtolower(trim($_POST['name'] ?? '')));
    $address = trim($_POST['address'] ?? '');

    if ($name == '') return ['error' => 'Name required'];

    $id = $this->model->create($name, $address);
    return $id ? ['id' => $id] : false;
  }

  public function update()
  {
    $id = intval($_POST['id'] ?? 0);
    $name = ucwords(strtolower(trim($_POST['name'] ?? '')));
    $address = trim($_POST['address'] ?? '');

    if ($id <= 0 || $name == '') return false;

    return $this->model->update($id, $name, $address);
  }

  public function destroy()
  {
    $id = intval($_POST['id'] ?? 0);
    return $id > 0 ? $this->model->delete($id) : false;
  }
}
