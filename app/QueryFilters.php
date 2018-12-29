<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

abstract class QueryFilters {

    protected $request;

    function __construct(Request $request) {
        $this->request = $request;
    }

    function apply(Builder $query) {
        
        $this->query = $query;

        foreach ($this->filters() as $name => $value) {
            if (!empty($value) && method_exists($this, $name)) {
                call_user_func_array([$this, $name], array_filter([$value]));
            }
        }
        
        return $this->query;
    }
    
    function filters()
    {
        return $this->request->all();
    }

    function getRequest()
    {
        return $this->request;
    }
}
