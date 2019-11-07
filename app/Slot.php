<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Slot extends Model
{
    protected $table = 'slots';
    protected $fillable = ['stylist_id', 'client_id', 'slot_begin', 'slot_length_min'];

    private $stylist_id;
    private $client_id;
    private $slot_begin;
    private $slot_length_min;
}
