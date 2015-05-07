<?php
/**
 * 管理機能へアクセスした際に自動的にログインする
 * 取り扱い注意
 */
class AutoLoginControllerEventListener extends BcControllerEventListener {
/**
 * 登録イベント
 * 
 * @var array
 */
	public $events = array(
		'startup'
	);
	
/**
 * startup
 * 
 * @param CakeEvent $event
 */
	public function startup(CakeEvent $event) {
		
		$Controller = $event->subject();
		
		// 管理画面にて、ログインしてない状態でアクセスしたら自動的に最も古いユーザにてログイン状態にする
		// デフォルトであれば初期登録管理者
		if(BcUtil::isAdminSystem() && !$Controller->BcAuth->user()) {
			$authModelName = $Controller->BcAuth->authenticate['Form']['userModel'];
			$authModel = ClassRegistry::init($authModelName);
			$user = $authModel->find('first');
			$user[$authModelName] = $user['UserGroup'];
			
			$Controller->BcAuth->login($user[$authModelName]);
		} 
	}
}

