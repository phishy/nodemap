<?php

namespace app\controllers;

use app\models\Node;
use app\models\NodeType;

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
	 * autocomplete node names
	 */
	function node_types() {
		$out = array();
		$nodes = NodeType::find('all');
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
		
		// recursion hell
		$map['cool']['sendit'] = false;

		$map['peopledb']['oracle'] = false;

		// reverse map
		foreach ($map as $k => $v) {
			$attr = array_keys($v);
			foreach ($attr as $a) {
				$map[$a][$k] = false;
			}
		}
		return $map;
	}
	
	function __build($key = null) {
		static $stack = array();
		if (in_array($key, $stack)) {
			return false;
		}
		$out = array();
		$map = $this->map();
		if (array_key_exists($key, $map)) {
			$stack[] = $key;
			$attr = array_keys($map[$key]);
			foreach ($attr as $a) {
				if (array_key_exists($a, $map)) {
					$out[$key] = $this->__build($a);
				} else {
					$out[$key][$a] = false;
				}
			}
		} else {
			$out[$key] = false;
		}
		array_pop($stack);
		return $out;
	}
	
	function build($key = '') {
		$out = $this->__build($key);
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