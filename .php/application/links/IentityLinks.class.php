<?php
namespace application\links;

interface IentityLinks{
	
	public function listingLinks(string $mode = 'LIST', $id = 0, $idParent = 0, $idType = 0);
	public function editingLinks(string $mode = 'VIEW', $id = 0, $idParent = 0, $idType = 0);
	public function detail(string $caption,string $action, $id = 0, $idParent = 0, $idType = 0);
	public function list(string $caption,$id = 0,$idParent = 0,$idType = 0);
	public function add(string $caption = 'New', $idParent = 0);
	public function view(string $caption = 'View', $id = 0);
	public function edit(string $caption = 'Edit', $id = 0);
	public function copy(string $caption = 'Copy', $id = 0);
	public function contextList();
	public function viewEdit(string $caption, $id = 0,string $editCaption='[#]');
	public function pagedListing($count, $page, $rows = 10, $idParent = 0, $idType = 0);
	
}



?>
