<?php
namespace application\links;

interface IentityLinks{
	
	public function listingLinks($mode = 'LIST',$id,$idParent,$idType);
	public function editingLinks($mode = 'VIEW', $id,$idParent,$idType);
	public function detail(string $caption, $action, $id,$idParent,$idType);
	public function list(string $caption,$id,$idParent,$idType);
	public function add($caption = 'New', $idParent);
	public function view($caption = 'View', $id);
	public function edit($caption = 'Edit', $id);
	public function copy($caption = 'Copy', $id);
	public function contextList();
	public function viewEdit($caption, $id,$editCaption='[#]');
	public function pagedListing($count, $page, $rows, $idParent, $idType);
	
}



?>
