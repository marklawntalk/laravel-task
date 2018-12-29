<?php

namespace App;

class UserFilters extends QueryFilters {
    
    function q($name)
    {
        return $this->query->where('name', 'LIKE', '%'.$name .'%');
    }
}
