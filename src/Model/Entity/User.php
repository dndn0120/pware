<?php
namespace PriWare\Model\Entity;

class User extends Entity
{
    // primitive
    public $idx;
    public $id;
    public $name;
    public $password;
    public $type;
    public $status;
    // object
    public $regdate;
    // overrides
    protected static $date_properties = ['regdate'];
    
    const STATUS_READY = 'ready';
    const STATUS_ACTIVE = 'active';
    const STATUS_DELETED = 'deleted';
}
