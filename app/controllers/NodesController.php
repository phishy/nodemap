<?php

namespace app\controllers;

use app\models\Node;

function debug($v) {
	print '<pre>';
	print_r($v);
}

/**
 * @TODO make sure dname gets searched before creating to avoid duplicates
 */
class NodesController extends \lithium\action\Controller {
	
	function index() {
		$nodes = array('Broad');
		return array($nodes);
	}
	
	/**
	 * adds a node to the tree
	 */
	function add() {
		if ($this->request->data) {
			$d = $this->request->data;
			$child = array(
				'type'  => $d['dtype'],
				'name'  => $d['dname'],
				'owner' => $d['owner'],
			);
			$node = Node::create($child);
			$node->save();
			
			$parent = array(
				'type'  => $d['type'],
				'name'  => $d['name'],
				'owner' => $d['owner'],
				'parents' => (string) $node->_id
			);
			
			$node = Node::create($parent);
			$node->save();
			
			if (true) {
				$this->_ok();
			} else {
				$this->_error();
			}
		}
	}

	/**
	 * autocomplete node names
	 */
	function autocomplete() {
		$out = array();
		$nodes = Node::find('all');
		foreach ($nodes as $node) {
			$n = array(
				'id'    => $node->id,
				'label' => $node->name,
				'value' => $node->name
			);
			$out[] = $n;
		}
		die(json_encode($out));
	}
	
	/**
	 * creates a flat map of nodes
	 */
	function _getMap($flat = false) {
		$nodes = Node::find('all');
		$map = array();
		foreach ($nodes as $node) {
			if ($flat) {
				$map[(string)$node->_id] =$node->name;
			} else {
				$map[(string)$node->_id] = $node;
			}
		}
		return $map;
	}
	
	/**
	 * returns a tree of nodes
	 */
	function get($id = null) {
		$out = array();
		$map = $this->_getMap();
		foreach ($map as $id => $node) {
			$n = array(
				'text' => $node->name
			);
			if (!empty($node->parents)) {
				$parents = explode(',', $node->parents);
				foreach ($parents as $parent_id) {
					$n['children'][] = array(
						'text' => $map[$parent_id]->name
					);
				}
			}
			$out[] = $n;
		}
		debug($out);exit;
		die(json_encode($out));
	}
	
	function map() {
		$map = array();
		$map['sendit']['itdb'] = false;
		$map['sendit']['it'] = false;
		$map['it']['intrepid'] = false;
		$map['intrepid']['stuff'] = false;
		$map['stuff']['cool'] = false;
		return $map;
	}
	
	// Array
	// (
	//     [sendit] => Array
	//         (
	//             [itdb] => 
	//             [it] => 
	//         )
	// 
	//     [it] => Array
	//         (
	//             [intrepid] => 
	//         )
	// 
	//     [intrepid] => Array
	//         (
	//             [stuff] => 
	//         )
	// 
	// )
	
	function __build($key = null) {
		static $i = 0;
		$i++;
		if ($i > 5) {
			debug('recursion hell');
			exit;
		}
		$out = array();
		$map = $this->map();
		// debug($map);exit;
		if (array_key_exists($key, $map)) {
			//debug("Found outer $key");
			$attr = array_keys($map[$key]);
			//debug($attr);
			foreach ($attr as $a) {
				if (array_key_exists($a, $map)) {
					//debug("Found inner $a");
					$out[$key] = $this->__build($a);
				} else {
					$out[$key][$a] = false;
				}
			}
		} else {
			$out[$key] = false;
		}
		//debug('returning...');
		return $out;
	}
	
	function build($key = 'sendit') {
		$out = $this->__build('sendit');
		debug($out);
		exit;
		// foreach ($map as $k => $v) {
		// 	if (is_array($v)) {
		// 		foreach ($v as $x => $y) {
		// 			if (array_key_exists($x, $map)) {
		// 				$attr = array_keys($map[$x]);
		// 				foreach ($attr as $a) {
		// 					$out[$k][$x][$a] = false;
		// 				}
		// 			} else {
		// 				$out[$k][$x] = false;
		// 			}
		// 		}
		// 	} else {
		// 		
		// 	}
		// }
		// debug($out);
		// exit;
	}
	
	/**
	 * send a json-encoded success message
	 */
	function _ok($msg = '') {
		die(json_encode(array('status' => 'ok', 'msg' => $msg)));
	}
	
	/**
	 * sends a json-encoded error message
	 */
	function _error($msg = '') {
		die(json_encode(array('status' => 'error', 'msg' => $msg)));
	}
	
}

?>