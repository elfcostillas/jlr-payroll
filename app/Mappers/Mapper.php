<?php

namespace App\Mappers;

use App\Libraries\ErrorMessage;
use Illuminate\Container\Container;
use App\Mappers\Frontiers\MapperInterface;

abstract class Mapper implements MapperInterface
{
    protected $model;
    protected $primary;
    protected $rules;
    protected $messages = [];
    protected $exceptions = [];

    public function __construct(Container $app)
    {
        $this->model = $app->make($this->modelClassName);
        $this->primary = $this->model->getKeyName();

    }       
    
    public function insertValid(array $request)
    {
        try {
            unset($request[$this->primary]);            
            $valid = $this->validateRequest($request);
            
            if ($valid === true) {
                $result = $this->model->create($request);
                return $result->{$this->primary};
            }
            return $valid;

        } catch(\Exception $e) {
            return $this->getException('create', $e);
        }
    }
    
    public function updateValid(array $request)
    {
        try {
            $request = $this->filterRequest($request);            
            $valid = $this->validateRequest($request);
            
            if ($valid === true) {
                
                $result = $this->model->where($this->primary, $request[$this->primary])
                    ->update($request);

                return $request[$this->primary];
            }
            return $valid;

        } catch (\Exception $e) {
            return $this->getException('update', $e);
        }
    }
    
    public function destroy(array $request)
    {
        try {
            $data = $this->model->findOrFail($request[$this->primary]);
            return $data->delete();
        } catch (\Exception $e) {
            return $this->getException('destroy', $e);
        }
    }

    public function validateRequest($request)
    {
        if (empty($this->messages)) {
            $validate = \Validator::make($request, $this->rules);
        } else {
            $validate = \Validator::make($request, $this->rules, $this->messages);
        }
        
        return $validate->fails() ? $validate->errors() : true;
    }

    public function filterRequest(array $request, $hiddenColumn = array(''))
    {
        $result = array();

        $columns = \Schema::getColumnListing($this->model->getTable());

        $data = $this->model->find($request[$this->primary])->makeVisible($hiddenColumn);

        foreach ($columns as $column) {
           
            if ($data->{$column} !== $request[$column]) {
                $result[$column] = $request[$column];
            }
        }

        $result[$this->primary] = $data[$this->primary];

        return $result;
    }

    public function getException($action, $e = null)
    {
        $message = '';
        $tmp = 'error';
        $errMsg = new ErrorMessage();
        switch ($action) {
            case 'create':
                // $message = empty($this->exceptions[$action]) ?
                //     'Unable to add new data.<br><br>Details: ' . (empty($e) ?
                //         '' : ' ' . $e->getMessage()) : $this->exceptions[$action];
                $message = $errMsg->friendlyMsg($e->getCode(), $action, $e);
                break;
            case 'update':
                $message = empty($this->exceptions[$action]) ?
                    'Unable to update data.<br><br>Details: ' . (empty($e) ?
                        '' : ' ' . $e->getMessage()) : $this->exceptions[$action];
                break;
            case 'destroy':
                $message = 'Unable to delete data.<br><br>Details: ';
                if (empty($this->exceptions[$action])) {
                    if (!empty($e)) {
                        if ($e->getCode() == 23503) {
                            $message .= 'Still used by other modules.';
                        } else {
                            $message .= $e->getMessage();
                        }
                    }
                } else {
                    $message .= $this->exceptions[$action];
                }
                $tmp = 'delete_error';
                break;
        }
        return (object) array($tmp => array('messages' => $message));
    }
}