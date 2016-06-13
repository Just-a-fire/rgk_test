<?php
namespace app\custom\sender\messager;

interface IMessager
{
	public function go($user, $params);
}
?>