<?php
/**
 * @name FormController
 * @author lancelot
 * @desc 默认控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
 */
class FormController extends Yaf_Controller_Abstract {

	public function IndexAction() {
		//1. fetch query
		$post = HttpServer::$post;

		//$post = $this->getRequest()->getPost ("fname");
		//var_dump( $post );
		$content['fname'] = $post["fname"];
		$content['lname'] = $post["lname"];
		
		//2. fetch model
		$model = new SampleModel();

		//3. assign
		$this->getView()->assign("content", $content);
		//$this->getView()->assign("name", $name);
		$this->display('index');
		//4. render by Yaf, 如果这里返回FALSE, Yaf将不会调用自动视图引擎Render模板
        return FALSE;
	}

	public function FormAction() {
		$get = HttpServer::$get;
		$this->getView()->assign("name", $get['name']);
		$this->display('form');
		return FALSE;
	}
}
