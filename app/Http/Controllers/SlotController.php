<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Slot;
use DateTime;
use Log;

class SlotController extends Controller
{

    public function addSlot($stylist_id, $slot_begin, $slot_length_min) {

		// First check whether this slot already exists
		// Including any that overlap in duration
		
		$date_time = new DateTime($slot_begin);
		$date_time->modify("+{$slot_length_min} minutes");
		$slot_end = $date_time->format('Y-m-d H:i:s');

	    $slot = Slot::where('stylist_id','=', $stylist_id)
	    	->whereBetween('slot_begin', [$slot_begin, $slot_end])
	    	->first();

    	if( is_null($slot) ) {
		    $new = Slot::create([
			    'stylist_id' 	  => $stylist_id,
			    'client_id' 	  => 0,
			    'slot_begin' 	  => $slot_begin,
			    'slot_length_min' => $slot_length_min,
		    ]);
		    
		    if( is_object($new) ) {
			    return true;
		    }
    	}
    	return false;
    }

    
    public function removeSlot($stylist_id, $slot_begin, $slot_length_min = 60) {

		// Find any slot for this stylist with the specified duration 
	    $slot = Slot::where('stylist_id','=', $stylist_id)
	    	->where('slot_begin','=', $slot_begin)
	    	->where('slot_length_min','<=', $slot_length_min)
    		->where('client_id','=', 0)
	    	->first();

	    if( isset($slot) && !$slot->client_id ) {
		    return $slot->delete();
	    } else {
		    return false;
	    }
    }
    

    public function bookAppointment($client_id, $stylist_id, $slot_begin, $slot_length_min) {
	    
		// First, make sure the appointment exists and is available
	    $slot = Slot::where('stylist_id','=', $stylist_id)
	    		->where('slot_begin','=', $slot_begin)
	    		->where('slot_length_min','>=', $slot_length_min)
	    		->where('client_id','=', 0)
	    		->first();

		if( isset($slot) ) {
			$slot->client_id = $client_id;
			return $slot->save();
		}
		
		return false;
    }


    public function cancelAppointment($client_id, $stylist_id, $slot_begin, $slot_length_min) {
	    
		// Find any slot for this stylist within the specified duration 
	    $slot = Slot::where('stylist_id','=', $stylist_id)
	    		->where('slot_begin','=', $slot_begin)
	    		->where('slot_length_min','<=', $slot_length_min)
	    		->where('client_id','=', $client_id)
	    		->first();

		if( isset($slot) ) {
			$slot->client_id = 0;
			return $slot->save();
		}
		
		return false;
    }

}
