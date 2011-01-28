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
	function _getMap() {
		$nodes = Node::find('all');
		$map = array();
		foreach ($nodes as $node) {
			$map[(string)$node->_id] = $node;
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
		//debug($out);exit;
		die(json_encode($out));
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