<?php 
namespace App;
class mPost{
    public $PostId;
	public $UserId;
    public $CategoryId;
    public $Title;
    public $Description;
    public $CreationDate;
    public $UpdatedDate;
    public $Status;
    public $Images = array();
	
	function __construct($db){

	}
}
?>