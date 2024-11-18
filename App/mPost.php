<?php 
namespace App;
class mUser{
    public $PostId;
	public $UserId;
    public $CategoryId;
    public $Title;
    public $Description;
    public $CreationDate;
    public $UpdatedDate;
    public $Status;
    public $Images = array();
    public $Files = array();
	
	function __construct($db){

	}
}
?>