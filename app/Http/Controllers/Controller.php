<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use DateTime;
use Log;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    

    function index(Request $request) {

	    $output = [];

		if( is_array($request->all()) ) {				
		    foreach($request->all() as $r) {

				// Reformat the ISO formatted datetime into one suitable for MySQL timestamp
				$date_time = new DateTime($r['slot_begin']);
				$slot_begin = $date_time->format('Y-m-d H:i:s');

			    switch($r['order_type']) {
				    case 'add_slot':
				    	$result = SlotController::addSlot(
				    		$r['stylist_id'], 
				    		$slot_begin, 
				    		$r['slot_length_min']
			    		);
				    	break;

				    case 'remove_slot':
				    	$result = SlotController::removeSlot(
				    		$r['stylist_id'], 
				    		$slot_begin, 
				    		$r['slot_length_min']
			    		);
				    	break;

				    case 'book_appointment':
				    	$result = SlotController::bookAppointment(
				    		$r['client_id'], 
				    		$r['stylist_id'], 
				    		$slot_begin, 
				    		$r['slot_length_min']
			    		);
				    	break;

				    case 'cancel_appointment':
				    	$result = SlotController::cancelAppointment(
				    		$r['client_id'], 
				    		$r['stylist_id'], 
				    		$slot_begin, 
				    		$r['slot_length_min']
			    		);
				    	break;

			    	default:
				    	$result = false;
			    }
	
			    $output[] = ['order_id' => $r['order_id'], 'succeed' => (int)$result];
			}
	    }
	    
	    return json_encode($output);	    
    }
}
